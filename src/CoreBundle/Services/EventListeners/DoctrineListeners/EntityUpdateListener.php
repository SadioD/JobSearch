<?php
// Ce Service est un callBack qui sera appelé avant chaque update d'une entité (Offer, User, etc.)
// Sa méthode preUpdate() sera exécutée à chaque PreUpdate Event
// Celle-ci appellera les méthodes d'autres Services [purgeOffersAttachments() pour Offer, etc.]
namespace CoreBundle\Services\EventListeners\DoctrineListeners;

use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\JobsPlateformBundle\Services\Purge;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class EntityUpdateListener
{
    // ATTR + CONSTR -------------------------------------------------------------------------------------------------
    /**
     * Service JobsPlateformBundle\Services\Purge
     */
    protected $purgator;

    public function __construct(Purge $purge) {
        $this->purgator = $purge;
    }// ------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------
    public function preUpdate(LifecycleEventArgs $arg)
    {
        $entity = $arg->getObject();

        // Si l'entité sur le point d'être mise à jour n'est pas une instance d'Offer, on ne fait rien
        if (!$entity instanceof Offer) {
            return;
        }

        $this->purgator->purgeOffersAttachments($entity, $arg);
    }// ------------------------------------------------------------------------------------------------------------------------
}