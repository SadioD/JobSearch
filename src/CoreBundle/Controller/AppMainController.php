<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppMainController extends Controller
{
    // HomePage - Route: / -----------------------------------------------------------------------------------------
    public function homeAction()
    {
        return $this->render('@Core/AppMain/core_homePage.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
    // ContactPage - Route: /contact-me -----------------------------------------------------------------------------------
    public function contactAction()
    {
        return $this->render('@Core/AppMain/core_contact.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
}
