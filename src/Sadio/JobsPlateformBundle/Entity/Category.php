<?php

namespace Sadio\JobsPlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="jobs_category")
 * @ORM\Entity(repositoryClass="Sadio\JobsPlateformBundle\Repository\CategoryRepository")
 */
class Category
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
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * Offer is The Owner of this Relation 
     * @ORM\ManyToMany(targetEntity="Sadio\JobsPlateformBundle\Entity\Offer", mappedBy="categories")
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
        $this->offers = new ArrayCollection();
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffers() { return $this->offers; }
    // ----------------------------------------------------------------------------------------------------------------------------------
    // SETTERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // OTHERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * Add offer
     * @param \Sadio\JobsPlateformBundle\Entity\Offer $offer
     * @return Category
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
    }// ----------------------------------------------------------------------------------------------------------------------------------
}
