<?php
// Ce Service permet d'envoyer des emails de notification
namespace Sadio\JobsPlateformBundle\Services\Mailer;

use Sadio\JobsPlateformBundle\Entity\Offer;

class Mailer 
{
    // ATTR + CONSTR --------------------------------------------------------------------------------------------------------------------
    /**
     * Contient l'objet qui permet d'envoyer un email
     */
    private $mailer;
    /**
     * Contient l'adresse email de l'expéditeur
     */
    private $adminEmail;

    public function __construct(\Swift_Mailer $mailerObject, $adminEmailAdress) {
        $this->mailer = $mailerObject;
        $this->adminEmail  = $adminEmailAdress;
    }// --------------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------
    public function sendNotification(Offer $offer) {
        // On définit le message (Sujet + corps)
        $message = new \Swift_Message('Confirmation de publication');
        $message->addPart('Votre offre ' . $offer->getPosition() . ' a bien été publiée sur le site ');
        
        // On définit le destinataire et l'expéditeur
        $message->setFrom($this->adminEmail)
                ->setTo($offer->getUser()->getEmail());

        // Enfin on envoie le message
        $this->mailer->send($message);
    }// --------------------------------------------------------------------------------------------------------------------------------
}