<?php

/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 10:21
 */
require_once ('Lieu.php');

class Consigne extends Lieu
{


    /**
     * Consigne constructor.
     */
    public function __construct($id, $nom, $codePostal, $ville, $latitude, $longitude)
    {
        parent::__construct($id, $nom, $codePostal, $ville, $latitude, $longitude);
    }

    function __toString()
    {
        return 'id=' . $this->getId() . ' nom=' . $this->getNom() . ' code postal=' . $this->getCodePostal() .
            ' ville=' . $this->getVille() . ' latitude=' . $this->getLatitude() . ' longitude=' . $this->getLongitude();
    }
}