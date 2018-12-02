<?php
/* Ce Service est appelé par le Subscriber (Listener) 
CoreBundle\Services\EventListeners\FOSListeners\RegistrationListener.
Il a pour objectif de modifier le role par défaut qui est attribué à l'user après sa création */

namespace CoreBundle\Services\FOSUser;

use FOS\UserBundle\Event\FormEvent;

class RolesManager 
{
    public function overrideDefaultRole(FormEvent $event)
    {
        $userRoles = ['ROLE_AUTEUR'];
        
        $user = $event->getForm()->getData();
        $user->setRoles($userRoles);
    }
}