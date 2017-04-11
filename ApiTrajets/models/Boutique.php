<?php

/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 11:10
 */
require_once 'Lieu.php';

class Boutique extends Lieu
{


    private $_adresse;

    /**
     * Boutique constructor.
     * @param $_adresse
     */
    public function __construct($id, $nom, $adresse, $codePostal, $ville, $latitude, $longitude)
    {
        parent::__construct($id, $nom, $codePostal, $ville, $latitude, $longitude);
        $this->setAdresse($adresse);
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->_adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->_adresse = $adresse;
    }

    function __toString()
    {
        return 'id=' . $this->getId() . ' nom=' . $this->getNom() . ' adresse=' . $this->getAdresse() .
            ' code postal=' . $this->getCodePostal() . ' ville=' . $this->getVille() .
            ' latitude=' . $this->getLatitude() . ' longitude=' . $this->getLongitude();
    }


}