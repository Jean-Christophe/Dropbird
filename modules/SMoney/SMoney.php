<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    Pierre-Louis Rebours  (DropBird)
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__).'/controllers/front/payment.php';

class SMoney extends PaymentModule
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'SMoney';
        $this->tab = 'payments_gateways';
        $this->version = '1.1.5';
        $this->author = 'Dropbird';
        $this->currencies = true;
        $this->currencies_mode = 'radio';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('S-money (e-commerce et market-place)');
        $this->description = $this->l("Ce module vous permet : L’encaissement par carte bancaire sur un (mode e-commerce) ou plusieurs (mode market-place) comptes marchands, la création, la gestion des comptes marchands et les reversements vers le comptes bancaires des marchands");

        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller '.$this->displayName.' ?');

        $this->limited_countries = array('FR');

        $this->limited_currencies = array('EUR');

    }

    public function install()
    {
        if (extension_loaded('curl') == false)
        {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        $iso_code = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));

        if (in_array($iso_code, $this->limited_countries) == false)
        {
            $this->_errors[] = $this->l('Ce module n\'est pas disponible dans votre pays');
            return false;
        }

        Configuration::updateValue('SMONEY_LIVE_MODE', false);

        return parent::install() &&
        $this->registerHook('header') &&
        $this->registerHook('backOfficeHeader') &&
        $this->registerHook('payment') &&
        $this->registerHook('paymentReturn') &&
        $this->registerHook('actionPaymentConfirmation') &&
        $this->registerHook('displayOrderConfirmation');
    }

    public function uninstall()
    {
        Configuration::deleteByName('SMONEY_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitSMoneyModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSMoneyModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
       $this->configForm =  array(
        'form' => array('legend' => array(
            'title' => $this->l('Settings'),
            'icon' => 'icon-c   ogs',
            ),
        'input' => array(
            array(
                'type' => 'switch',
                'label' => $this->l('Activer S-money'),
                'name' => 'SMONEY_LIVE_MODE',
                'is_bool' => true,
                'desc' => $this->l('Activer votre module'),
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                        ),
                    array(
                        'id' => 'active_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                        )
                    ),
                ),
            array('type' => 'text', 'desc' => $this->l('Entrer votre clef d\'API ici'), 'name' => 'SMONEY_ACCOUNT_TOKEN', 'required' => true, 'label' => $this->l('Clef d\'API')),
            array('type' => 'text','desc' => $this->l('Entrer votre url ici'),'name' => 'SMONEY_ACCOUNT_URL','required' => true,'label' => $this->l('URL')),
            ),
        'submit' => array(
            'title' => $this->l('Save'),
            ),
        ),        );

     if (DpbBoutiques::getAllBoutiques())
       {
        $dpbBoutiques = DpbBoutiques::getAllBoutiques();
        $suppl = Supplier::getSuppliers();
        foreach ($dpbBoutiques as $key => $value) {

          $this->configForm['form']['input'][] = array('type' => 'text','desc' => $this->l('Nom du marchand enregistré chez Smoney'),
            'name' => 'SMONEY_NAME_'.$value['id_supplier'],'required' => true, 'label' => 'Marchand : '.$value['name']);  

          $this->configForm['form']['input'][] = array('type' => 'text','desc' => $this->l('Taux de commission (default 7%)'),
            'name' => 'SMONEY_COMMISSION_'.$value['id_supplier'],'size' => 20,
            'suffix' => '%','label' => $value['name']);
      }
  }
  else {
      $this->configForm['form']['input'][] = array('type' => 'list','desc' => $this->l('Vous devez créer un ou des Fournisseurs en lien avec Smoney pour pouvoir utiliser ce module'),
        'name' => 'SMONEY_COM','size' => 20,'label' => $this->l('Commission'));        
  }

//$this->CheckConfiguration("GET", "users");


  return $this->configForm;
}

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {

        $suppl = Supplier::getSuppliers();

        $this->getConfigForm = array(
            'SMONEY_LIVE_MODE' => Configuration::get('SMONEY_LIVE_MODE', false),
            'SMONEY_ACCOUNT_TOKEN' => Configuration::get('SMONEY_ACCOUNT_TOKEN', null),
            'SMONEY_ACCOUNT_URL' => Configuration::get('SMONEY_ACCOUNT_URL', null),
            'SMONEY_COM' => Configuration::get('SMONEY_COM', null)
            );

        foreach ($suppl as $key => $value) {
          $this->getConfigForm['SMONEY_NAME_'.$value['id_supplier']] = Configuration::get('SMONEY_NAME_'.$value['id_supplier'], null);
          $this->getConfigForm['SMONEY_COMMISSION_'.$value['id_supplier']] = Configuration::get('SMONEY_COMMISSION_'.$value['id_supplier'], 7);
      }
      return $this->getConfigForm;
  }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */

    public function hookPayment($params)
    {
        $live = Configuration::get('SMONEY_LIVE_MODE');
        $url = trim(Configuration::get('SMONEY_ACCOUNT_URL'));
        $token = trim(Configuration::get('SMONEY_ACCOUNT_TOKEN'));

        if (!$url || !$token || $live == false) {
            return;
        } 

        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false)
            return false;

        $this->smarty->assign('module_dir', $this->_path);
        $this->context->cookie->dirmail = dirname(__FILE__). '/mails';


        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }

    /**
     * This hook is used to display the order confirmation page.
     */
    public function hookPaymentReturn($params)
    {
        if ($this->active == false)
            return;

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR'))
            $this->smarty->assign('status', 'ok');

        $this->smarty->assign(array(
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
            ));

        return $this->display(__FILE__, 'views/templates/hook/confirmation.tpl');
    }

    public function hookActionPaymentConfirmation()
    {
        /* Place your code here. */
    }

    public function hookDisplayOrderConfirmation()
    {
        /* Place your code here. */
    }

     public function CheckConfiguration($typeCall, $nameUrl)
      {
        $url = trim(Configuration::get('SMONEY_ACCOUNT_URL'));
        $token = trim(Configuration::get('SMONEY_ACCOUNT_TOKEN'));

        if (!$url || !$token) {
          $this->displayError("une erreur interne est parvenu, veuillez contacter l'administrateur (url/token)");
          return;
        } 

        $header = array(
         'Accept: application/vnd.s-money.v2+json',
         'Content-Type: application/vnd.s-money.v2+json',
         'Authorization: Bearer '. $token);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url. $nameUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $authToken = curl_exec($ch);

        if (curl_errno($ch) != 0) {
          $this->context->cookie->test_error = 504;


        }
        curl_close($ch);

        $curl_jason = json_decode($authToken);
//        $this->context->cookie->test_error = $curl_jason->{'ErrorCode'};
      }

}
