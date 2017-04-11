<?php

/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 10:06
 */
abstract class Lieu
{
    private $_id;
    private $_nom;
    private $_codePostal;
    private $_ville;
    private $_latitude;
    private $_longitude;

    /**
     * Lieu constructor.
     * @param $nom
     * @param $codePostal
     * @param $ville
     * @param $latitude
     * @param $longitude
     */
    public function __construct($id, $nom, $codePostal, $ville, $latitude, $longitude)
    {
        $this->setId($id);
        $this->setNom($nom);
        $this->setCodePostal($codePostal);
        $this->setVille($ville);
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->_nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->_nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getCodePostal()
    {
        return $this->_codePostal;
    }

    /**
     * @param mixed $code
     */
    public function setCodePostal($code)
    {
        $this->_codePostal = $code;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->_ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville)
    {
        $this->_ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->_latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->_longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    abstract function __toString();
}