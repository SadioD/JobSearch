<?php
/* Ce service est appelé par le Listener 
   CoreBundle\Services\EventListeners\DoctrineListeners\EntityUpdateListener.
   Il a pour objectif de purger de la BDD et du dossier assets les pièces jointes orphelines */
namespace Sadio\JobsPlateformBundle\Services;

use Sadio\JobsPlateformBundle\Entity\Offer;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class Purge
{
    /**
     * Purge les pièces Jointes de Offer
     * Cas où l'user a mis à jour l'offre en supprimant l'ancienne PJ => attribut url non vide
     */
    public function purgeOffersAttachments(Offer $offer, LifecycleEventArgs $arg)
    {
        // Si aucun fichier n'a été posté mais que l'attribut url n'est pas vide =>  on suuprime Attachment de Offer
        if ($offer->getAttachment()->getFile() == null && $offer->getAttachment()->getUrl() !== null) 
        {
            // On recupère l'entity manager
            $em = $arg->getObjectManager();
            
            // On recupère l'id de l'Attachment à Supprimer
            $attachmentId = $offer->getAttachment()->getId();
                
            // On save la mise à jour de l'offre (sans l'attachment)
            $offer->setAttachment(null);
            $em->persist($offer);
                
            // On supprime l'ancien attachment de la BDD et du dossier assets/docs (via postDelete() de Attachment)
            $em->remove($em->find(Attachment::class, $attachmentId));
            $em->flush();
        } 
        else {
            return;
        }
    }
}