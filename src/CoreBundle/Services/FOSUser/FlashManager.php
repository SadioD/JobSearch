<?php
/* Ce Service est appelé par le Subscriber (Listener) 
CoreBundle\Services\EventListeners\FOSListeners\RegistrationListener.
Il a pour objectif de modifier le message flash de confirmation d'ajout de nouvel User */

namespace CoreBundle\Services\FOSUser;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashManager 
{
    // ATTR + CONSTR --------------------------------------------------------------------------------------------------------------------
    /**
     * Service Session
     */
    protected $session;

    public function __construct(SessionInterface $sessionService) {
        $this->session = $sessionService;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------------------------------     
    public function overrideDefaultFlash()
    {
        $allFlashMessages = $this->session->getFlashBag()->all();
        $this->session->getFlashBag()->add('success', 'Your Account has been createeed!');
    }// ----------------------------------------------------------------------------------------------------------------------------------
}