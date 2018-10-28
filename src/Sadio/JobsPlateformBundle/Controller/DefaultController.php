<?php

namespace Sadio\JobsPlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sadio\AntiSpamBundle\Service\AntiSpam;

class DefaultController extends Controller
{
    // HomePage - Route: /platform/hello-world --------------------------------------------------------------------------------------
    public function indexAction($page) {
        if ($page != "" && $page < 1) {
            // throw new NotFoundHttpException('Page "'.$page.'" does not exist.');
            throw $this->createNotFoundException('Page "'.$page.'" does not exist.');
        }
        // On recupère la liste d'offres depuis la BDD
        $list = [
            [
                'id'            => '1',
                'position'      => 'Développeur Symfony',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'  => '',
                'author'        => 'Banque Mondiale'
            ],
            [
                'id'            => '2',
                'position'      => 'Développeur Full-Stack',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'Le Crédit Lyonnais'
            ],
            [
                'id'            => '3',
                'position'      => 'Développeur Back-End',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'La Poste Mobile'
            ]
        ];
        // On crée une short Description pour chaque offre si la description est trop longue
        for($i=0; $i<count($list); $i++) {
            if (strlen($list[$i]['description']) > 500) {
                $list[$i]['shortDesc'] = substr($list[$i]['description'], 0, 500);
                $list[$i]['shortDesc'] = substr($list[$i]['shortDesc'], 0, strrpos($list[$i]['shortDesc'], ' ')) . '...';
            }          
        }
        
        // On affiche la vue  en usant de la méthode longue --> vue dans Sadio/JobsPlateform/Ressources/views/Default/views/default/index.html.twig
        return $this->render('@SadioJobsPlateform/Default/index.html.twig', ['list' => $list]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Single Post - Route: /platform/offer/{id} -------------------------------------------------------------------------------
    public function viewAction($id) {
        if ($id == '1') {
            $offer = [
                'id'            => '1',
                'position'      => 'Développeur Symfony',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'Banque Mondiale'
            ];
        } 
        elseif ($id == '2') {
            $offer = [
                'id'            => '2',
                'position'      => 'Développeur Full-Stack',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'Le Crédit Lyonnais'
            ];
        }
        elseif ($id == '3') {
            $offer = [
                'id'            => '3',
                'position'      => 'Développeur Back-End',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'La Poste Mobile'
            ];   
        } else {
            throw new NotFoundHttpException('L\'offre que vous recherchez n\'est pas disponible');
        }
        return $this->render('@SadioJobsPlateform/Default/view.html.twig', ['offer' => $offer]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Add New Post - Route: /platform/new-offer -------------------------------------------------------------------------------
    public function newAction(Request $request) {
        if($request->isMethod('POST')) {
            // Save le Formulaire 
            
            $this->processForm('id');
        }
        return $this->render('@SadioJobsPlateform/Default/new.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Update Post - Route: /platform/edit-offer/{id} --------------------------------------------------------------------------
    public function editAction($id, Request $request) {
        if($request->isMethod('POST')) {
            // Save le Formulaire dans la BDD

            $this->processForm('id');
        }
        return $this->render('@SadioJobsPlateform/Default/edit.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Delete Post - Route: /platform/delete-offer/{id} ------------------------------------------------------------------------
    public function deleteAction($id) {
        // return $this->render('@SadioJobsPlateform/Default/test.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Traite les formulaire d'Ajout et Modification d'Offre ------------------------------------------------------------------------
    public function processForm($id) {
        // Save le Flash + redirect
        $request->getSession()->getFlashBag()->add('notice', 'The offer has been saved!');
        return $this->redirectToRoute('sadioJobsP_singlePost', ['id' => $id]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Affiche les 3 derniers post sur le template parent base.html ------------------------------------------------------------------------
    public function recentlyPostedAction() {
        $recentlyPosted = [
            [
                'id'            => '1',
                'position'      => 'Développeur Symfony',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'Banque Mondiale'
            ],
            [
                'id'            => '2',
                'position'      => 'Développeur Full-Stack',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'Le Crédit Lyonnais'
            ],
            [
                'id'            => '3',
                'position'      => 'Développeur Back-End',
                'shortDesc'     => '',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'creationDate'          => '',
                'author'        => 'La Poste Mobile'
            ]
        ];
        
        return $this->render('@SadioJobsPlateform/Default/platform_recentlyPosted.html.twig', ['recentlyPosted' => $recentlyPosted]);
    }// -----------------------------------------------------------------------------------------------------------------------------
}
