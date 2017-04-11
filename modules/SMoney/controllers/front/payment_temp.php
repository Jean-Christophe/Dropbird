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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/


class SMoneyPaymentModuleFrontController extends ModuleFrontController
{

  private $orderId;
  private $appAccountId;

  public function setOrderId ($OrderId) { return ($this->orderId=$OrderId); }
  public function setAppAccountId ($AppAccountId) { return ($this->appAccountId=$AppAccountId); }

  public function postProcess()
  {
    session_start();
    if(!isset($_SESSION['static']) && empty($_SESSION['static'])) {
       $_SESSION['static'] = 1;
      }
    else
      $_SESSION['static'] = $_SESSION['static'] + 1;

    $this->orderId = "";
    $this->appAccountId = "";
    $this->base_return_url = "";

    if ($this->context->cookie->id_cart) {
      $Cart = $this->context->cart;
    }
    else {
     $this->displayError('Your shopping cart is empty.'); 
     return;
   }
   $product_array = $Cart->getProducts();
 
   $cart_id =  Context::getContext()->cart->id;

   $this->setOrderId($this->generateReference());

   $token = trim(Configuration::get('SMONEY_ACCOUNT_TOKEN'));

   $this->list = $this->getPayments();
   $this->list = $this->getList($this->list);

   $module_id = $this->module->id;

   $this->httpPostUrl("POST", "payins/cardpayments/", $this->list);
 }

public static function generateReference()
{
    $last_id = Db::getInstance()->getValue('
        SELECT MAX(id_order)
        FROM '._DB_PREFIX_.'orders');

    return str_pad((int)$last_id + 2, 9, STR_PAD_LEFT);
 
}

public function getPayments() {

  if ($this->context->cookie->id_cart) {
    $Cart = $this->context->cart;
  }

  $product_array = $Cart->getProducts();
  $payments = array();                            // Pierre F. : Déclaration du tableau par défaut
  $i = 0;                                         // Pierre F. : Variable utilisée pour l'ajout au tableau de livraison

  foreach($product_array as $key => $product_item)
  {

    $supplier = new Supplier((int)$product_item['id_supplier']);
    $com = ($product_item['total_wt'] * trim(Configuration::get('SMONEY_COMMISSION_'.$product_item['id_supplier']))) / 100;

    $total_price = (float)$product_item['total_wt'];  // (float)$product_item['total_wt'] * 100;

    $payments[$key] = array(
      'orderId' => $this->orderId.'-'.($key + 1),
      'beneficiary' => array("appaccountid" => trim(Configuration::get('SMONEY_NAME_'.$product_item['id_supplier']))),
      'amount'  =>  floor(($total_price - $com) * 100),   // ($total_price - $com) * 100
      'message' => 'nom :'. $product_item['name'],
      'fee' => ceil($com * 100)                           // $com * 100
      );
  } 

  // Pierre F. : Ajout au tableau du montant de la livraison

  $req_sql = '
    SELECT value
    FROM `ps_configuration`
    WHERE `name` = "PS_SHIPPING_FREE_PRICE"
    ';                                        // Pierre F. : Récupération de la valeur minimum pour une livraison gratuite

  $req_sql1 = '
    SELECT value
    FROM `ps_configuration`
    WHERE `name` = "PS_TAX"
    ';                                        // Pierre F. : Récupération du prix de la livraison

  $freeShipping = Db::getInstance()->getValue($req_sql);  
  $taxShipping = Db::getInstance()->getValue($req_sql1);  

  // $freeShipping = $freeShipping * 100;
 
  if ($total_price < $freeShipping)           // Pierre F. : Dropbird -> 25€
  {
       $payments[] = array(
         'orderId' => $this->orderId.'-L-'.$i,
         'beneficiary' => ["appaccountid" => 'dropbird-com'],
         'amount' => $taxShipping * 100,      // Pierre F. : Dropbird -> 1€
         'message' => 'livraison '.$this->orderId,
         'fee' => 0
       );
  }

  return $payments;
}


public function getList($payments)
{
 $this->base_return_url = $this->context->link->getModuleLink('SMoney','redirect');

 $mylist = array (
  'orderId'         => $this->orderId,
  'availableCards'  =>  'CB;MASTERCARD;VISA',
  'payments'        => $payments,
  'ismine' => false,
  'urlReturn'       =>  $this->base_return_url
  );
 return json_encode($mylist);
}


public function httpPostUrl($typeCall, $nameUrl, $data)
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
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                   
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

  $authToken = curl_exec($ch);

  if (curl_errno($ch) != 0) {
    $this->displayError("Error call API, check your connection");
    return;
  }
  curl_close($ch);
  $curl_jason = json_decode($authToken);

  if ($curl_jason->{'Code'} != 0 && $curl_jason->{'Code'} != 710) {
    $this->context->cookie->test_error = $curl_jason->{'Code'};
    $this->displayError($curl_jason->{'ErrorMessage'});
  }
  else if ($curl_jason->{'Code'} == 710)
  {
  /**
   * Order ID already exist
   */
   $this->setOrderId (str_pad((int)$this->orderId . '-'. ($_SESSION['static'] + 1), 9, STR_PAD_LEFT));
   $this->list = $this->getPayments();
   $this->list = $this->getList($this->list);
   $this->httpPostUrl("POST", "payins/cardpayments/", $this->list);
  }
  else
  {
    Tools::redirect($curl_jason->{'Href'});
  }

}



protected function displayError($message, $description = false)
{
        /**
         * Create the breadcrumb for your ModuleFrontController.
         */
        $this->context->smarty->assign('path', '
          <a href="'.$this->context->link->getPageLink('order', null, null, 'step=3').'">'.$this->module->l('Payment').'</a>
          <span class="navigation-pipe">&gt;</span>'.$this->module->l('Error'));

        /**
         * Set error message and description for the template.
         */
        array_push($this->errors, $this->module->l($message), $description);

        return $this->setTemplate('error.tpl');
      }
    }