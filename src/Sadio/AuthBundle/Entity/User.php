<?php

namespace Sadio\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="auth_user")
 * @ORM\Entity(repositoryClass="Sadio\AuthBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    // ATTR + CONSTR --------------------------------------------------------------------------------------------------------------------
    // Cette classe hérite de FOSUserBundle => elle recupère les attribut et méthodes de ce dernier
    /* Liste de certains Attributs: 
            username, 
            email, 
            enabled (true => Inscription validée, false => Inscription non validée)
            password
            lastLogin
            roles
       
       Liste de certaines Méthodes: 
            getters
            setters
            hasRole($role) => return bool
            removeRole($role)
            addRole($role)
            setRoles(array $roles) */
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
        parent::__construct();

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
