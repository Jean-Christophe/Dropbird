<?php

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');


$smoney = Module::getInstanceByName('SMoney');

if(Tools::isSubmit('bouton')){
	$champs_1 = Tools::getValue('Nom_boutique');
	$champs_2 = Tools::getValue('FirstName');
	$champs_3 = Tools::getValue('LastName');
	$champs_4 = Tools::getValue('Birthday');
	$champs_5 = Tools::getValue('Street');
	$champs_6 = Tools::getValue('Zipcode');
	$champs_7 = Tools::getValue('City');
	$champs_8 = Tools::getValue('Company');

	   if (!$champs_1 || !$champs_2 || !$champs_3 || !$champs_4 || !$champs_5 ||
		   	!$champs_6 || !$champs_7 || !$champs_8) {
	   		echo("Vous devez rentrer tous les champs demandés");
	   		return ;
        } 

 	$url = trim(Configuration::get('SMONEY_ACCOUNT_URL'));
  	$token = trim(Configuration::get('SMONEY_ACCOUNT_TOKEN'));

	 $mylist = array (
	  'appuserid' => $champs_1,
	  'type'      => 2,
	  'profile'   =>  array('firstname' => $champs_2,
					        'lastname'  => $champs_3,
      						'birthdate' => $champs_4,
      						'address'   => array(
								'street'    => $champs_5,
								'zipcode'   => $champs_6,
								'city'		=> $champs_7, 
								'country' => "FR")),
	  'company'   =>  array('name' => $champs_8)
	  );

/* 	 $mylist = array (
	  'appuserid' => $champs_1,
	  'type'      => 2,
	  'profile'   =>  array('civility'  => 0,
	  						'firstname' => $champs_2,
					        'lastname'  => $champs_3,
      						'birthdate' => "1985-09-29T00:00:00", //$champs_4,
					        'address'   => array(
									        'street'    => "blabla",
									        'zipcode'   => "blabla",
									        "city"		=> "Rennes", 
									        'country' => "FR"
									        ),
					        "phonenumber" => "0600000001",
					        "email" => "test@dropbird.fr",
					        "alias" => $champs_1,
					        ),
	  'company'   =>  array('name' 	=> $champs_5,
	  						'SIRET' => '123456789')
	  );
*/
	$header = array(
	   'Accept: application/vnd.s-money.v1+json',
	   'Content-Type: application/vnd.s-money.v1+json',
	   'Authorization: Bearer '. $token);


	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url.'users');
	  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mylist));

	  $authToken = curl_exec($ch);


	  if (curl_errno($ch) != 0) {
	    $this->displayError("Error call API, check your connection");
	    return;
	  }

	  curl_close($ch);
	 $curl_jason = json_decode($authToken);
	  if ($curl_jason->{'Code'} != 0 && $curl_jason->{'Code'} != 710) {
   		echo ("Une erreur c'est produite : ".$curl_jason->{'ErrorMessage'});
 	  }
 	  else
 	  {
//		$this->context->smarty->assign('success', "Le compte à été crée");
    	Tools::redirect($_SERVER['HTTP_REFERER']);
      }
}

?>