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

{if (isset($status) == true) && ($status == 'ok')}
<h3 style="color:green">{l s='Votre commande numéro %s a été accepté.' sprintf=$shop_name mod='SMoney'}</h3>
<p>
	<br />- {l s='Montant' mod='SMoney'} : <span class="price"><strong>{$total|escape:'htmlall':'UTF-8'}</strong></span>
	<br />- {l s='Reference' mod='SMoney'} : <span class="reference"><strong>{$reference|escape:'html':'UTF-8'}</strong></span>

	<br /><br />{l s='Un email vous à été envoyé avec les informations.' mod='SMoney'}
	<br /><br />{l s='si vous avez des questions, commentaires, veuillez contacter notre' mod='SMoney'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert client.' mod='SMoney'}</a>
	<p class="cart_navigation exclusive">

</p>
{else}
<h3 style="color:red">{l s='Votre commande numéro : %s n\'a pas été accepté.' sprintf=$shop_name mod='SMoney'}</h3>
<p>
	<br />- {l s='Reference' mod='SMoney'} <span class="reference"> <strong>{$reference|escape:'html':'UTF-8'}</strong></span>
	<br /><br />{l s='veuillez recommencer votre commande' mod='SMoney'}
	<br /><br />{l s='si vous avez des questions, commentaires, veuillez contacter notre' mod='SMoney'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert client.' mod='SMoney'}</a>
	<p class="cart_navigation exclusive">

</p>	

{/if}
<hr />
