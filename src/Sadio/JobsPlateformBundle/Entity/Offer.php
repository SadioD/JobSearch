<?php

namespace Sadio\JobsPlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Offer
 *
 * @ORM\Table(name="jobs_offer")
 * @ORM\Entity(repositoryClass="Sadio\JobsPlateformBundle\Repository\OfferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Offer
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
     * @ORM\Column(name="position", type="string", length=255)
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(name="shortDesc", type="text")
     */
    private $shortDesc;

    /**
     * @var string
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * Offer is The Owner of This Relation
     * @ORM\ManyToMany(targetEntity="Sadio\JobsPlateformBundle\Entity\Category", inversedBy="offers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * Offer is The Owner of This Relation
     * @ORM\ManyToOne(targetEntity="Sadio\AuthBundle\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="slug", type="text", unique=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $slug;

    /**
     * @var \DateTime
     * @ORM\Column(name="editionDate", type="datetime")
     * @ORM\JoinColumn(nullable=true)
     */
    private $editionDate;
    

    public function __construct(array $donnees = []) {
        foreach($donnees as $key => $value) 
        {
			$method = 'set' . ucfirst($key);
			if (is_callable([$this, $method])) {
				$this->$method($value);
			}
        }
        $this->creationDate = new \DateTime();
        $this->categories   = new ArrayCollection();
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // GETTERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @return int
     */
    public function getId() { return $this->id; }
    /**
     * @return string
     */
    public function getPosition() { return $this->position; }
    /**
     * @return string
     */
    public function getShortDesc() { return $this->shortDesc; }
    /**
     * @return string
     */
    public function getDescription() { return $this->description; }
    /**
     * @return \DateTime
     */
    public function getCreationDate() { return $this->creationDate; }
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories() { return $this->categories; }
    /**
     * @return \Sadio\AuthBundle\Entity\User
     */
    public function getUser() { return $this->user; }
    // ----------------------------------------------------------------------------------------------------------------------------------
    // SETTERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @param string $position
     * @return Offer
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
    /**
     * @param string $shortDesc
     * @return Offer
     */
    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;

        return $this;
    }
    /**
     * @param string $description
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * @param \DateTime $creationDate
     * @return Offer
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }
    /**
     * @param \Sadio\AuthBundle\Entity\User $user
     * @return Offer
     */
    public function setUser(\Sadio\AuthBundle\Entity\User $user)
    {
        $this->user = $user;

        // Vu que la relation est bidirectionnelle on lie également l'offre à User
        // => il faut utiliser $offer->setUser($user)
        $user->addOffer($this);
        
        return $this;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // OTHERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * Add category
     * @param \Sadio\JobsPlateformBundle\Entity\Category $category
     * @return Offer
     */
    public function addCategory(\Sadio\JobsPlateformBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        // Since the relation is reversed, we also add $this (porduct) to the $category
        $category->addOffer($this);

        return $this;
    }
    /**
     * Remove category
     * @param \Sadio\JobsPlateformBundle\Entity\Category $category
     */
    public function removeCategory(\Sadio\JobsPlateformBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);

        // Since the relation is reversed, we also remove $this (porduct) from the $category
        $category->removeOffer($this);
    }
    /**
     * Increase the number of Offers the user have posted.
     * The Right Event is PrePersist (The count will increase right before the new Offer is Persisted)
     * Because we need the change of offers count to be persisted in DataBase
     * @ORM\PrePersist
     */
    public function increaseOffersCount() {
        $this->user->increaseNumberOfOffers();
    }
    /**
     * Decrease the number of Offers the user have posted.
     * The Right Event is PreRemove (The count will decrease right before the Offer is removed)
     * Because If we chose PostRemove, We won't have access to the $offer's methods since it will be removed
     * @ORM\PreRemove
     */
    public function decreaseOffersCount() {
        $this->user->decreaseNumberOfOffers();
    }// ----------------------------------------------------------------------------------------------------------------------------------
}
