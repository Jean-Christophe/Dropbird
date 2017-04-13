<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 12/04/2017
 * Time: 09:51
 */

namespace TrajetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Consigne
 * @package TrajetsBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="dpb_consignes")
 */
class Consigne implements \JsonSerializable
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
     * @ORM\Column(name="code_postal", type="integer")
     */
    private $cpo;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $ville;
    /**
     * @ORM\Column(type="float", precision=10, scale=7)
     */
    private $latitude;
    /**
     * @ORM\Column(type="float", precision=10, scale=7)
     */
    private $longitude;

    /**
     * Consigne constructor.
     * @param $id
     * @param $nom
     * @param $cpo
     * @param $ville
     * @param $latitude
     * @param $longitude
     */
    public function __construct($id, $nom, $cpo, $ville, $latitude, $longitude)
    {
        $this->setId($id);
        $this->setNom($nom);
        $this->setCpo($cpo);
        $this->setVille($ville);
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
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
    public function getCpo()
    {
        return $this->cpo;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
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
     * @param mixed $cpo
     */
    public function setCpo($cpo)
    {
        $this->cpo = $cpo;
        return $this;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    function __toString()
    {
        return 'id= ' . $this->getId() . ', nom= ' . $this->getNom() . ', cpo= ' . $this->getCpo() . ', ville= ' . $this->getVille() .
            ', latitude= ' . $this->getLatitude() . ', longitude= ' . $this->getLongitude();
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}