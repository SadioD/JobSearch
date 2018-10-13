<?php

namespace Sadio\AntiSpamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SadioAntiSpamBundle:Default:index.html.twig');
    }
}
