<?php
/**
 * Created by PhpStorm.
 * User: jeanc_000
 * Date: 26/04/2017
 * Time: 10:10
 */

namespace TrajetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Utilisateur
 * @package TrajetsBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="dpb_utilisateurs")
 */
class Utilisateur extends BaseUser implements \Serializable, \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(message="Veuillez saisir votre nom.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min="3",
     *     max="80",
     *     minMessage="Le nom est trop court (3 caractères minimum).",
     *     maxMessage="Le nom est trop long (80 caractères maximum).",
     *     groups={"Registration", "Profile"})
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(message="Veuillez saisir votre prénom.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min="2",
     *     max="80",
     *     minMessage="Le prénom est trop court (2 caractères minimum).",
     *     maxMessage="Le prénom est trop long (80 caractères maximum).",
     *     groups={"Registration", "Profile"})
     */
    protected $prenom;


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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Utilisateur constructor.
     * @param $id
     * @param $nom
     * @param $prenom
     * @param $email
     * @param $password
     * @param $statut
     * @param $estActif
     */
    /*public function __construct($nom, $prenom)
    {
        parent::__construct();
        $this->setNom($nom);
        $this->setPrenom($prenom);
    }*/

    function __toString()
    {
        return 'id= ' . $this->getId() . ', nom= ' . $this->getNom() . ', prenom= ' . $this->getPrenom() . ', e-mail= ' . $this->getEmail() .
                ', password= ' . $this->getPassword() . ', roles= ' .$this->getRoles();
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

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->getId(),
            $this->getEmail(),
            $this->getPassword()
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }
}