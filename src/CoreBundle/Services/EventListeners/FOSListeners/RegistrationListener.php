<?php
/* Ce Service est un Subscriber (listener) des évènements REGISTRATION_SUCCESS et REGISTRATION_COMPLETED
de FOSUserBundle qui sera appelé après chaque soumission valide du formulaire (sans erreur dans le 
form) d'enregistrement de nouvel utilisateur => avant le persist. Il appellera les méthodes d'autres services 
(Ex: La méthode overrideDefaultRole() du service RolesManager, overrideDefaultFlash de FlashManager, etc.). 
Il est configuré dans app/services.yml */

namespace CoreBundle\Services\EventListeners\FOSListeners;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use CoreBundle\Services\FOSUser\RolesManager;
use CoreBundle\Services\FOSUser\FlashManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrationListener implements EventSubscriberInterface
{
    // ATTR + CONSTR --------------------------------------------------------------------------------------------------------------------
    /**
     * Service RolesManager
     */
    protected $rolesManager;

    /**
     * Service FlashManager
     */
    protected $flashManager;

    public function __construct(RolesManager $roles_manager, FlashManager $flash_manager) 
    {
        $this->rolesManager = $roles_manager;
        $this->flashManager = $flash_manager;
    }// ----------------------------------------------------------------------------------------------------------------------------------
    // METHODS -------------------------------------------------------------------------------------------------------------------------     
    /**
     * Cette méthode doit obligatoirement être implémentée (à cause de EventSubscriberInterface)
     * Elle permet de lister les évènements que ce Subscriber écoute
     * REGISTRATION_COMPLETED a une priorité négative => Elle sera appelée en retard par rapport 
     * aux autres listeners FOSUserBundle qui écoutent le même évènement
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS    => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_COMPLETED  => ['onRegistrationCompleted', -3],
        );
    }
    /**
     * Cette méthode permet d'appeler le service RolesManager
     * Ce dernier se chargera de modifier les roles par défaut et changer le flashMessage
     */
    public function onRegistrationSuccess(FormEvent $event) {
        $this->rolesManager->overrideDefaultRole($event);
    }
    /**
     * Cette méthode permet d'appeler le service FlashManager
     * Ce dernier se chargera de modifier le message flash par défault
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $event) {
        $this->flashManager->overrideDefaultFlash();
    }// ----------------------------------------------------------------------------------------------------------------------------------
}