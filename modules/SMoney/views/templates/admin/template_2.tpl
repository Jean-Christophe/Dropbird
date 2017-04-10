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

<div class="panel">
		<div class="row SMoney-header">
			<div class="col-md-offset-2 col-xs-offset-1 col-xs-8 col-md-8 text-center">
				<h2>{l s='Création d\'un compte utilisateur professionnel chez smoney' mod='SMoney'}</h2>		
			</div>
	</div>
	<hr />
	

<div class="panel">
		<div class="row">
			<div class="col-md-offset-4 col-xs-offset-1 col-md-3">
				<div class="panel">
						<form action="{$module_dir|escape:'html':'UTF-8'}formulaire.php" method="post">
							{l s='Nom de la boutique + code postal (Dropbird_35)' mod='SMoney'}
							<input type="text" name="Nom_boutique" />
							{l s='Prénom du marchand' mod='SMoney'}
							<input type="text" name="FirstName">
							{l s='Nom du marchand' mod='SMoney'}
							<input type="text" name="LastName">
							{l s='Rue' mod='SMoney'}
							<input type="text" name="Street">
							{l s='Zipcode' mod='SMoney'}
							<input type="text" name="Zipcode">
							{l s='Ville' mod='SMoney'}
							<input type="text" name="City">
							{l s='Date de naissance' mod='SMoney'}
							</br>
							<input type="date" name="Birthday">
							</br>
							{l s='Compagnie' mod='SMoney'}
							<input type="text" name="Company">
							<input type="submit" name="bouton" />
						</form>
			</div>
		</div>
	</div>
</div>