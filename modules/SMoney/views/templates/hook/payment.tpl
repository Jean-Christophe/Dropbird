{*
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
	*}


{assign var=params value=[
'module_action' => '1'
]}

		<div class="pull-right">
			<p class="payment_module" id="SMoney_payment_button">
				{if $cart->getOrderTotal() < 2}
				<a href="Javascript:void(0)">
					<img src="{$domain|cat:$payment_button|escape:'html':'UTF-8'}" alt="{l s='Paiement par carte bancaire' mod='SMoney'}" />
					{l s='Minimum amount required in order to pay with my payment module:' mod='SMoney'} {convertPrice price=2}
				</a>
				{else}
				<!--  <a href="{$this_path|escape}controllers/front/SmoneyApi.php" title="{l s='Pay with my SMoney' mod='SMoney'}">-->
				<a href="{$link->getModuleLink('SMoney', 'payment', $params, true)|escape:'htmlall':'UTF-8'}" title="{l s='Paiement par carte bancaire' mod='SMoney'}">
					<img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/logo_part.png" alt="{l s='Paiement par carte bancaire' mod='SMoney'}" width="auto" height="50" style="margin-right: 15px"/>
					<br />
					{l s='Paiement par carte bancaire' mod='SMoney'}
				</a>
				
				<!-- <div class="cart_navigation clearfix">
       				 <a href="{$link->getModuleLink('SMoney', 'payment', $params, true)|escape:'htmlall':'UTF-8'}" title="Payer" class="button-exclusive btn btn-default">
       				     <i class="icon-chevron-right"></i>
       				     {l s='Payer votre commande par carte bancaire' mod='SMoney'}
       				 </a>
   				</div> -->
					
				{/if}
			</p>
		</div>
