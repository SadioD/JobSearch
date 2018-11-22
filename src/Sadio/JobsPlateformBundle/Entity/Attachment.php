<?php

namespace Sadio\JobsPlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Attachment
 *
 * @ORM\Table(name="jobs_attachment")
 * @ORM\Entity(repositoryClass="Sadio\JobsPlateformBundle\Repository\AttachmentRepository")
 * @ORM\HasLifecycleCallbacks()
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
    private $fileTempName;

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

        // Cas d'un update de PJ : S'il existe déjà une pièce jointe saved dans url, =>
        // On la transfert dasn fileTempName pour la supprimer par la ensuite. 
        // Aussi on réinitialise l'url pour accueillir la nouvelle PJ
        if ($this->url !== null) {
            $this->fileTempName = $this->url;
            $this->url = null;
        }
        return $this;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // OTHERS ---------------------------------------------------------------------------------------------------------------------------
    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     * Elle sera exécutée avant chaque Persist et Update.
     * Elle permet d'enregistrer le nom du fichier joint dans l'attribut url de Attachment avant le persist
     */
    public function preSave() {
        // Si aucune pièce jointe n'a été postée, on ne fait rien
        if ($this->file === null) { 
            return; 
        }
        // Le nom sera composé de l'extension du fichier + timestamp
        $this->url = time() . '.' . $this->file->guessExtension();
    }
    /**
     * @ORM\PostUpdate
     * @ORM\PostPersist
     * Elle sera exécutée après chaque Persist et Update
     * Elle permet de déplacer le fichier joint vers assets/docs et de supprimer les anciennes PJ (en cas d'update)
     */
    public function upload() {
        // Si aucune pièce jointe n'a été postée, on ne fait rien
        if ($this->file === null) { 
            return; 
        }
        // On déplace la PJ en lui donnant son nouveau nom (extension + timestamp)
        $this->file->move($this->getRootPath(), $this->url);

        // On supprime les anciens fichiers (cas de mise à jour de fichier)
        $this->removeOldAttachmentIfExist();
    }
    /**
     * @ORM\PreRemove
     * Elle sera exécutée avant chaque Remove.
     * Elle permet d'enregistrer le nom de l'attachment (depuis son attribut url) dans la variable fileTempName, pour effectivement supprimer l'image par la suite
     */
    public function preDelete() {
        $this->fileTempName = $this->url;
    }
    /**
     * @ORM\PostRemove
     * Elle sera exécutée après chaque Remove.
     * Elle permet d'effectivement supprimer la pièce jointe de assets/docs
     */
    public function postDelete() {
        $this->removeOldAttachmentIfExist();
    }
    /**
     * Elle permet de retourner le chemin du dossier upload pour l'enregistrement du fichier joint
     */
    public function getRootPath() {
        return __DIR__ . '/../../../../web/assets/docs';
    }
    /**
     * Elle Permet de supprimer les pièces jointes (Cas de mise à jour et cas de suppression d'offre)
     */
    public function removeOldAttachmentIfExist() {
        if ($this->fileTempName !== null) {
            if (file_exists($this->getRootPath() . '/' . $this->fileTempName)) {
                //var_dump($this->getRootPath() . '/' . $this->fileTempName);
                unlink($this->getRootPath() . '/' . $this->fileTempName);
            }
        }
    }// ----------------------------------------------------------------------------------------------------------------------------------
}

