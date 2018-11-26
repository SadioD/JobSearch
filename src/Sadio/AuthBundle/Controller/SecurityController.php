<?php

namespace Sadio\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    // Cette méthode modifie la méthode loginAction du controller SecurityController de FosUserBundle
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        // Si l'User est déjà connecté => on ne le laisse pas accéder à la page
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException('Access denied : You\'re already online');
        }
        
        // Si l'User n'est pas connecté => on lui affiche le contenu de la méthode parent loginAction() de FosUserBundle
        $response = parent::loginAction($request);
        
        // On aurait pu faire quelque chose à ce niveau avant d'afficher la réponse du controller parent

        return $response;
    }
}
