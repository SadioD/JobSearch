<?php
/* Ce service a pour objectif de purger de la BDD et du dossier assets les pièces jointes orphelines 
Il est principalement utilisable dans le JobsPlatform/DefaultController, method edit() */
namespace Sadio\JobsPlateformBundle\Services;

use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\JobsPlateformBundle\Entity\Attachment;
use Doctrine\ORM\EntityManagerInterface;

class Purge
{
    
    // ATTR + CONSTR -------------------------------------------------------------------------------------------------
    /**
     * Contient l'entity manager
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }// ------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------
    /**
     * Purge les pièces Jointes de Offer
     * Cas où l'user a mis à jour l'offre en supprimant l'ancienne PJ => attribut url non vide
     */
    public function purgedOldAttachments(Offer $offer)
    {
        // Si aucun fichier n'a été posté et qu'il n'existe aucune ancienne PJ => on ne fait rien
        if ($offer->getAttachment() == null) {
            return false;
        }
        // Si aucun fichier n'a été posté mais que l'attribut url n'est pas vide =>  on suuprime Attachment de Offer
        elseif ($offer->getAttachment()->getFile() == null && $offer->getAttachment()->getUrl() !== null) 
        {    
            // On recupère l'id de l'Attachment à Supprimer
            $attachmentId = $offer->getAttachment()->getId();
                
            // On save la mise à jour de l'offre (sans l'attachment)
            $offer->setAttachment(null);
            $this->em->persist($offer);
                
            // On supprime l'ancien attachment de la BDD et du dossier assets/docs (via postDelete() de Attachment)
            $this->em->remove($this->em->find(Attachment::class, $attachmentId));
            $this->em->flush();
   
            return true;
        } 
        else {
            return false;
        }
    }// ------------------------------------------------------------------------------------------------------------------------
    
}