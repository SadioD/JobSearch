<?php

namespace Sadio\JobsPlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Attachment
 *
 * @ORM\Table(name="jobs_attachment")
 * @ORM\Entity(repositoryClass="Sadio\JobsPlateformBundle\Repository\AttachmentRepository")
 */
class Attachment
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
     * @ORM\Column(name="url", type="string", length=191, nullable=false, unique=false)
     */
    private $url;

    /**
     * Permet de sauvegarder temporairement le nom du fichier joint
     */
    private $tempName;

    /**
     * @var UploadedFile
     * Permet d'hydrater la pièce jointe
     */
    private $file;
    
    public function __construct(array $donnees = []) {
        foreach($donnees as $key => $value) 
        {
			$method = 'set' . ucfirst($key);
			if (is_callable([$this, $method])) {
				$this->$method($value);
			}
        }
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // GETTERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @return int
     */
    public function getId() { return $this->id; }
    /**
     * @return string
     */
    public function getUrl() { return $this->url; }
    /**
     * @return UploadedFile
     */
    public function getFile() { return $this->file; }
    // ----------------------------------------------------------------------------------------------------------------------------------
    // SETTERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @param string $url
     * @return Attachment
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
    /**
     * @param UploadedFile $file
     * @return Attachment
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        return $this;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // OTHERS ---------------------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------------------------------
}

