<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 10:45
 */


require_once '../models/Consigne.php';
require_once '../models/Boutique.php';

$consigne = new Consigne(1, 'Lieu', 35000, 'Rennes', 2332, 23343);
echo $consigne;
$boutique = new Boutique(2, 'Consigne', 'L\'adresse', 35000, 'Rennes', 433, 343);
echo $boutique;