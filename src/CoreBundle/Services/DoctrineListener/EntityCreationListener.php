<?php
// Ce Service est un callBack qui sera appelé après chaque persist d'un entité (Offer, User, etc.)
// Sa méthode postPersist() sera exécutée à chaque PostPersist Event
// Celle-ci appellera les méthodes d'autres Services [sendNotification() pour Offer, purgeUserList() pour User, etc.]
namespace CoreBundle\Services\DoctrineListener;

use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\AuthBundle\Entity\User;
use Sadio\AuthBundle\Services\Purge\Purge;
use CoreBundle\Services\Mailer\Mailer;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class EntityCreationListener 
{
    // ATTR + CONSTR -------------------------------------------------------------------------------------------------
    /**
     * Service Mailer
     */
    private $mailer;

    /**
     * Service Purge
     */
    private $purgator;

    public function __construct(Mailer $mailerObject, Purge $purgatorObject) {
        $this->mailer    = $mailerObject;
        $this->purgator = $purgatorObject;
    }// ------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------
    public function postPersist(LifecycleEventArgs $arg) {
        // On recupère l'entité instanciée
        $entity = $arg->getObject();

        if ($entity instanceof Offer) 
        {
            // Si c'est un entité de Offer on envoie l'email de confirmation de publication
            $email          = $entity->getUser()->getEmail();
            $messageTitle   = 'Confirmation de publication';
            $messageContent = 'Votre offre ' . $entity->getPosition() . ' a bien été publiée sur le site.';
            
            $this->mailer->sendNotification($email, $messageTitle, $messageContent);
        } 
        elseif ($entity instanceof User) 
        {
            // Si c'est un entité de User, on appelle la métode permettant de purger la liste des User
            $this->purgator->purgeUserList($arg);
        } else {
            // Si c'est une autre entité, on ne fait rien
            return;
        }
    }// ------------------------------------------------------------------------------------------------------------------------
}