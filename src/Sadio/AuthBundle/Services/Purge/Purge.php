<?php
// Ce service a pour principal objectif de purger la table USER de la BDD
// Il supprimera tout utilisateur n'ayant posté aucune offre et ayant été créé depuis plus de 10 jours
// Il appellera ensuite le Service Mailer pour envoyer une notification
// Ce service sera appelé lors de chaque PostPersist de nouvel utilisateur
namespace Sadio\AuthBundle\Services\Purge;

use Sadio\AuthBundle\Entity\User;
use CoreBundle\Services\Mailer\Mailer;
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
    public function purgeUserList(User $user, LifecycleEventArgs $arg) 
    {
        // On recupère l'entity manager
        // Et la liste des users pour vérifier s'il faut les supprimer
        $em = $arg->getObjectManager();
        $userList = $em->getRepository(User::class)->findAll();
        
        foreach ($userList as $user) {
            // pour chaque User, calcule la date de référence pour la comparer à celle de Creation du User
            $referenceDate = new \DateTime($user->getCreationDate());
            $referenceDate->modify('+10 day');
            
            // Ensuite supprime les Users qui n'ont publié aucune offre et dont le compte a plus  de 10 jours 
            // Mais avant on leur envoie un email d'information
            if ($user->getNumberOfOffers() < 1 && $user->getCreationDate() >= $referenceDate) 
            {
                $message = 'Depuis la création de votre compte,  vous n\'avez publié aucune offre. Votre va être supprimé';
                $this->mailer->sendNotification($user->getEmail(), 'Notice : Suppression de compte', $message);
                
                $em->remove($user);
            }
        }
        $em->flush();
    }// ------------------------------------------------------------------------------------------------------------------------
}