<?php
// Ce Service est un callBack qui sera appelé après chaque persist (new Offer) => postPersist Event
// Sa méthode postPersist() sera exécutée => elle appellera la méthode sendNotification() du service Mailer
namespace Sadio\JobsPlateformBundle\Services\DoctrineListener;

use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\JobsPlateformBundle\Services\Mailer\Mailer;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class OfferCreationListener 
{
    // ATTR + CONSTR --------------------------------------------------------------------------------------------------------------------
    /**
     * Contient l'objet qui envoie des emails
     */
    private $mailer;

    public function __construct(Mailer $mailerObject) {
        $this->mailer = $mailerObject;
    }// --------------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------
    public function postPersist(LifecycleEventArgs $arg) {
        // On recupère l'instance qui ets sur le point d'être persisté ou update
        // On vérifie s'il s'agit d'un objet Offer; Si non => on ne fait rien        
        $entity = $arg->getObject();
        if (!$entity instanceof Offer) {
            return;
        }

        // Ensuite on appelle le service qui envoie l'email en lui passant l'objet Offer concerné
        $this->mailer->sendNotification($entity);
    }// --------------------------------------------------------------------------------------------------------------------------------
}