<?php
// Ce service a pour principal objectif de purger la table USER de la BDD
// Il supprimera tout utilisateur n'ayant posté aucune offre et ayant été créé depuis plus de 10 jours
// Il appellera ensuite le Service Mailer pour envoyer une notification
// Ce service sera appelé lors de chaque PostPersist de nouvel utilisateur par 
// CoreBundle\Services\EventListeners\DoctrineListeners\EntityCreationListener
namespace Sadio\AuthBundle\Services;

use Sadio\AuthBundle\Entity\User;
use CoreBundle\Services\Mailer;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class Purge 
{
   // ATTR + CONSTR -------------------------------------------------------------------------------------------------
    /**
     * Service CoreBundle\Service\Mailer\Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailerObject) {
        $this->mailer = $mailerObject;
    }// ------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------
    public function purgeUserList(LifecycleEventArgs $arg) 
    {
        // On recupère l'entity manager
        // Et la liste des users pour vérifier s'il faut les supprimer
        $em = $arg->getObjectManager();
        $userList = $em->getRepository(User::class)->findAll();

        // Si la BDD ne contient aucun User, on ne fait rien
        if (!$userList) {
            return;
        }
        
        foreach ($userList as $user) 
        {
            // Si l'user n'a pas le rôle ADMIN et qu'il n'a rien posté après 10 jour => On le purge de la BDD
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                // pour chaque User, calcule la date de référence pour la comparer à celle d'aujourd'hui
                $todaysDate    = new \DateTime();
                $referenceDate = new \DateTime($user->getCreationDate()->format('Y-m-d H:i:s'));
                $referenceDate->modify('+10 day');
            
                // Ensuite supprime les Users qui n'ont publié aucune offre et dont le compte a plus  de 10 jours 
                // Mais avant on leur envoie un email d'information
                if ($user->getNumberOfOffers() < 1 && $todaysDate->format('Y-m-d') >= $referenceDate->format('Y-m-d')) 
                {
                    $message = 'Depuis la création de votre compte,  vous n\'avez publié aucune offre. Votre va être supprimé';
                    $this->mailer->sendNotification($user->getEmail(), 'Notice : Suppression de compte', $message);
                
                    $em->remove($user);
                }
            }
        }
        $em->flush();
    }// ------------------------------------------------------------------------------------------------------------------------
}