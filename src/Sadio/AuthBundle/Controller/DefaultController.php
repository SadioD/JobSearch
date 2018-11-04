<?php

namespace Sadio\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SadioAuthBundle:Default:index.html.twig');
    }
}
