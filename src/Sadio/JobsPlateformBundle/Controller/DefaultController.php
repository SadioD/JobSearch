<?php

namespace Sadio\JobsPlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    // HomePage - Route: /platform/hello-world --------------------------------------------------------------------------------------
    public function indexAction($page) {
        if($page != "" && $page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" does not exist.');
        }
        // Soit on use de la méthode courte --> vue dans app/Ressources/views/default/index.html.twig
        return $this->render('default/index.html.twig', ['userName' => 'Douze']);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Single Post - Route: /platform/offer/{id} -------------------------------------------------------------------------------
    public function viewAction($id) {
        $offer = '';
        return $this->render('default/view.html.twig', ['offer' => $offer]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Add New Post - Route: /platform/new-offer -------------------------------------------------------------------------------
    public function newAction(Request $request) {
        if($request->isMethod('POST')) {
            // Save le Formulaire 
            
            $this->processForm('id');
        }
        return $this->render('default/new.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Update Post - Route: /platform/edit-offer/{id} --------------------------------------------------------------------------
    public function updateAction($id, Request $request) {
        if($request->isMethod('POST')) {
            // Save le Formulaire dans la BDD

            $this->processForm('id');
        }
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Delete Post - Route: /platform/delete-offer/{id} ------------------------------------------------------------------------
    public function deleteAction($id) {

    }// -----------------------------------------------------------------------------------------------------------------------------
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Traite les formulaire d'Ajout et Modification d'Offre ------------------------------------------------------------------------
    public function processForm($id) {
        // Save le Flash + redirect
        $request->getSession()->getFlashBag()->add('notice', 'The offer has been saved!');
        return $this->redirectToRoute('sadioJobsP_singlePost', ['id' => $id]);
    }// -----------------------------------------------------------------------------------------------------------------------------
}
