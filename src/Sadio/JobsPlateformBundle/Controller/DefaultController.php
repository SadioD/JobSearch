<?php

namespace Sadio\JobsPlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    // HomePage - Route : /hello-world
    public function indexAction() {
        // Soit on use de la méthode courte --> vue dans app/Ressources/views/default/index.html.twig
        return $this->render('default/index.html.twig', ['userName' => 'Douze']);

        // Soi on use la méthode longue --> vue dans JobsPlatform/Ressources/views/default/index.html.twig
        // return $this->render('@SadioJobsPlateform/Default/index.html.twig', ['userName' => 'Douze']);
    }
}
