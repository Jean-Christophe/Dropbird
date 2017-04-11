<?php

/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 11/04/2017
 * Time: 09:53
 */
class Collecteur
{

    private $_id;
    private $_nom;
    private $_prenom;
    private $_mail;

    /**
     * Collecteur constructor.
     * @param $nom
     * @param $prenom
     * @param $mail
     */
    public function __construct($nom, $prenom, $mail)
    {
        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setMail($mail);
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $_id
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
    public function getPrenom()
    {
        return $this->_prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->_prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->_mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->_mail = $mail;
    }

    function __toString()
    {
        return 'id=' . $this->getId() . ' nom=' . $this->getNom() . ' prénom=' . $this->getPrenom() . ' mail=' . $this.$this->getMail();
    }
}