<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 12/04/2017
 * Time: 10:01
 */

namespace TrajetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Collecteur
 * @package TrajetsBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="collecteurs")
 */
class Collecteur
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nom;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $prenom;
    /**
     * @ORM\Column(type="string", length=250)
     */
    private $email;

    /**
     * Collecteur constructor.
     * @param $id
     * @param $nom
     * @param $prenom
     * @param $email
     */
    public function __construct($id, $nom, $prenom, $email)
    {
        $this->setId($id);
        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setEmail($email);
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    function __toString()
    {
        return 'id= ' . $this->getId() . ', nom= ' . $this->getNom() . ', prenom= ' . $this->getPrenom() . ', e-mail= ' .$this->getEmail();
    }
}