<?php
// Ce Service permet d'envoyer des emails de notification
namespace CoreBundle\Services\Mailer;

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
    public function sendNotification($email, $messageTitle, $messageContent) {
        // On définit le message (Sujet + corps)
        $message = new \Swift_Message($messageTitle);
        $message->addPart($messageContent);
        
        // On définit le destinataire et l'expéditeur
        $message->setTo($email)
                ->setFrom($this->adminEmail);

        // Enfin on envoie le message
        $this->mailer->send($message);
    }// --------------------------------------------------------------------------------------------------------------------------------
}