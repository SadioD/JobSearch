<?php

namespace Sadio\JobsPlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Offer
 *
 * @ORM\Table(name="jobs_offer")
 * @ORM\Entity(repositoryClass="Sadio\JobsPlateformBundle\Repository\OfferRepository")
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
     * @var string
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * Offer is The Owner of This Relation
     * @ORM\ManyToMany(targetEntity="Sadio\JobsPlateformBundle\Entity\Category", inversedBy="offers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

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
     * @return string
     */
    public function getAuthor() { return $this->author; }
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories() { return $this->categories; }
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
     * @param string $author
     * @return Offer
     */
    public function setAuthor($author)
    {
        $this->author = $author;

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
    }// ----------------------------------------------------------------------------------------------------------------------------------
}