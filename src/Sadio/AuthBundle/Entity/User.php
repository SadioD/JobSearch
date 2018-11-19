<?php

namespace Sadio\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="auth_user")
 * @ORM\Entity(repositoryClass="Sadio\AuthBundle\Repository\UserRepository")
 */
class User
{
    // ATTR + CONSTR --------------------------------------------------------------------------------------------------------------------
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, unique=true)
     */
    private $password;

    /**
     * @var int
     * @ORM\Column(name="numberOfOffers", type="integer", nullable=true)
     */
    private $numberOfOffers;

    /**
     * @var \DateTime
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * Offer is The Owner of this Relation 
     * @ORM\OneToMany(targetEntity="Sadio\JobsPlateformBundle\Entity\Offer", mappedBy="user", cascade={"remove"})
     */
    private $offers;

    public function __construct(array $donnees = []) {
        foreach($donnees as $key => $value) 
        {
			$method = 'set' . ucfirst($key);
			if (is_callable([$this, $method])) {
				$this->$method($value);
			}
        }
        $this->creationDate = new \DateTime();
        $this->offers = new ArrayCollection();
        $this->numberOfOffers = 0;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // GETTERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @return int
     */
    public function getId() { return $this->id; }
    /**
     * @return string
     */
    public function getName() { return $this->name; }
    /**
     * @return string
     */
    public function getEmail() { return $this->email; }
    /**
     * @return string
     */
    public function getPassword() { return $this->password; }
    /**
     * @return int
     */
    public function getNumberOfOffers() { return $this->numberOfOffers; }
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffers() { return $this->offers; }
    /**
     * @return \DateTime
     */
    public function getCreationDate() { return $this->creationDate; }
    // ----------------------------------------------------------------------------------------------------------------------------------
    // SETTERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    /**
     * @param integer $numberOfOffers
     * @return User
     */
    public function setNumberOfOffers($numberOfOffers)
    {
        $this->numberOfOffers = $numberOfOffers;

        return $this;
    }
    /**
     * @param \DateTime $creationDate
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // OTHERS ---------------------------------------------------------------------------------------------------------------------------    
    /**
     * Add offer
     * @param \Sadio\JobsPlateformBundle\Entity\Offer $offer
     * @return User
     */
    public function addOffer(\Sadio\JobsPlateformBundle\Entity\Offer $offer)
    {
        $this->offers[] = $offer;

        return $this;
    }
    /**
     * Remove offer
     * @param \Sadio\JobsPlateformBundle\Entity\Offer $offer
     */
    public function removeOffer(\Sadio\JobsPlateformBundle\Entity\Offer $offer)
    {
        $this->offers->removeElement($offer);
    }
    /**
     * Increase the number of Offers the user have posted. Add 1 anytime a new Offer is persisted
     */
    public function increaseNumberOfOffers() {
        $this->numberOfOffers++;
    }
    /**
     * Decrease the number of Offers the user have posted. Remove 1 anytime an Offer is removed
     */
    public function decreaseNumberOfOffers() {
        $this->numberOfOffers--;
    }// ----------------------------------------------------------------------------------------------------------------------------------
}
