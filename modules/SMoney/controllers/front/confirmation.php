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

class SMoneyConfirmationModuleFrontController extends ModuleFrontController
{
public function postProcess()
{


$error = $this->context->cookie->test_error;

if ($error != 0)
{
$message = $this->getPaymentErrorMessage($error);

switch ($error)
{
case '1':
$status = Configuration::get('PS_OS_ERROR');
break;
case '2':
$status = Configuration::get('PS_OS_ERROR');
break;
case '3':
$status = Configuration::get('PS_OS_ERROR');
break;
case '4':
$status = Configuration::get('PS_OS_PAYMENT');
break;
case '5':
$status = Configuration::get('PS_OS_ERROR');
break;
case '6':
$status = Configuration::get('PS_OS_ERROR');
break;
default:
$status = Configuration::get('PS_OS_ERROR');
break;
}
}
else {
$status = Configuration::get('PS_OS_PAYMENT');
$message = "Transaction success";
}

$cart_id =  Context::getContext()->cart->id;
$secure_key = Context::getContext()->customer->secure_key;

$cart = new Cart((int)$cart_id);
$customer = new Customer((int)$cart->id_customer);




if (!Validate::isLoadedObject($customer))
Tools::redirect('index.php?controller=order&step=1');

/**
* Converting cart into a valid order
*/
$module_name = $this->module->displayName;
$currency_id = (int)Context::getContext()->currency->id;

$this->module->validateOrder($cart_id, $status, $cart->getOrderTotal(), $module_name, $message, array(), $currency_id, false, $secure_key);
/**
* If the order has been validated we try to retrieve it
*/
$order_id = Order::getOrderByCartId((int)$cart->id);
unset($_SESSION['static']);
if ($order_id && ($secure_key == $customer->secure_key)) {
/**
* The order has been placed so we redirect the customer on the confirmation page.
*/
$module_id = $this->module->id;
Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart_id.'&id_module='.$module_id.'&id_order='.$order_id.'&key='.$secure_key);
} else {
/**
* An error occured and is shown on a new page.
*/
$this->errors[] = $this->module->l('An error occured. Please contact the merchant to have more informations');
return $this->setTemplate('error.tpl');
}
}

public function getPaymentErrorMessage($errorCode)
{
$this->payment_errors = array(
1 => 'Le commerçant doit contacter la banque du porteur',
2 => 'Paiement refusé',
3 => 'Paiement annulé par le client',
4 => 'Porteur non enrôlé 3D-Secure',
5 => 'Erreur authentification 3D-Secure',
6 => 'Erreur technique SystemPay',
710 => 'Erreur 710'
);

return $this->payment_errors[$errorCode];
}



}
