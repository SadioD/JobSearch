<?php

namespace Sadio\JobsPlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\JobsPlateformBundle\Entity\Category;
use Sadio\AuthBundle\Entity\User;

class DefaultController extends Controller
{
    // HomePage - Route: /platform/hello-world --------------------------------------------------------------------------------------
    public function indexAction($page) {
        if ($page != "" && $page < 1) {
            // throw new NotFoundHttpException('Page "'.$page.'" does not exist.');
            throw $this->createNotFoundException('Page "'.$page.'" does not exist.');
        }
        // On recupère le nombre d'offres à afficher par page depuis le fichier config.yml
        // Et on recupère aussi la liste d'offres depuis la BDD (notons qu'il s'agit d'un objet Paginator)
        $offersPerPage = $this->container->getParameter('offersPerPage');
        $list = $this->getDoctrine()->getRepository(Offer::class)->findAllWithUser($page, $offersPerPage);        

        // On calcule le nombre total de pages (nombre de liens cliquables)
        $numberOfPages = ceil(count($list) / $offersPerPage);

        // Si le paramètre page (GET) recu est supérieur au nombre total de pages calculé, on retourne une 404
        if ($page > $numberOfPages) {
            throw $this->createNotFoundException('Page "'.$page.'" does not exist.');
        }
        // On affiche la vue  en usant de la méthode longue --> vue dans Sadio/JobsPlateform/Ressources/views/Default/views/default/index.html.twig
        return $this->render('@SadioJobsPlateform/Default/index.html.twig', ['list'          => $list,
                                                                             'page'          => $page,
                                                                             'numberOfPages' => $numberOfPages]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Single Post - Route: /platform/offer/{id} -------------------------------------------------------------------------------
    public function viewAction($slug) {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneWithAllRelations($slug);

        if (!$offer) {
            throw $this->createNotFoundException('No job was found for given information - '. $slug);
        }
        return $this->render('@SadioJobsPlateform/Default/view.html.twig', ['offer' => $offer]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Add New Post - Route: /platform/new-offer -------------------------------------------------------------------------------
    public function newAction(Request $request) {
        $offer = new Offer(['position'      => 'Sexy Grasset',
                            'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.']);
        
        $firstCategory = new Category(['name' => 'Sex Sex']);

        $offer->addCategory($firstCategory);
        $offer->setUser($this->getDoctrine()->getManager()->find(User::class, 13));
        $this->getDoctrine()->getManager()->persist($offer);

        $this->getDoctrine()->getManager()->flush();


        if($request->isMethod('POST')) {
            // Save le Formulaire 
            // On crée une short Description pour chaque offre si la description est trop longue
            /*for($i=0; $i<count($list); $i++) {
            if (strlen($list[$i]['description']) > 500) {
                $list[$i]['shortDesc'] = substr($list[$i]['description'], 0, 500);
                $list[$i]['shortDesc'] = substr($list[$i]['shortDesc'], 0, strrpos($list[$i]['shortDesc'], ' ')) . '...';
            }          
            }*/
            $this->processForm('id');
        }
        return $this->render('@SadioJobsPlateform/Default/new.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Update Post - Route: /platform/edit-offer/{id} --------------------------------------------------------------------------
    public function editAction($slug, Request $request) {
        if($request->isMethod('POST')) {
            // Save le Formulaire dans la BDD

            $this->processForm('slug');
        }
        return $this->render('@SadioJobsPlateform/Default/edit.html.twig');
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Delete Post - Route: /platform/delete-offer/{id} ------------------------------------------------------------------------
    public function deleteAction($id) {
        $offer = $this->getDoctrine()->getManager()->find(Offer::class, $id);

        if (!$offer) {
            throw $this->createNotFoundException('No job was found for given information - '. $id);
        }
        $this->getDoctrine()->getManager()->remove($offer);
        $this->getDoctrine()->getManager()->flush();

        // On save un Flash et on redirige
        $this->addFlash('notice', 'The offer has been deleted!');
        return $this->redirectToRoute('sadioJobsP_homepage');
    }// -----------------------------------------------------------------------------------------------------------------------------
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Traite les formulaire d'Ajout et Modification d'Offre ------------------------------------------------------------------------
    public function processForm($slug) {
        // Save le Flash + redirect
        $this->addFlash('notice', 'The offer has been saved!');
        return $this->redirectToRoute('sadioJobsP_singlePost', ['slug' => $slug]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Affiche les 3 derniers post sur le template parent base.html ------------------------------------------------------------------------
    public function recentlyPostedAction() {
        $recentlyPosted = $this->getDoctrine()->getRepository(Offer::class)->findBy([], ['id' => 'DESC'], 3);
        
        return $this->render('@SadioJobsPlateform/Default/platform_recentlyPosted.html.twig', ['recentlyPosted' => $recentlyPosted]);
    }// -----------------------------------------------------------------------------------------------------------------------------
}
