<?php

namespace Sadio\JobsPlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\JobsPlateformBundle\Entity\Attachment;
use Sadio\JobsPlateformBundle\Entity\Category;
use Sadio\AuthBundle\Entity\User;
use Sadio\JobsPlateformBundle\Form\OfferType;
use Sadio\JobsPlateformBundle\Form\OfferEditType;

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
    public function newAction(Request $request, SessionInterface $session) {
        $offer = new Offer();
        $form  = $this->createForm(OfferType::class, $offer);

        // Si le formulaire a été soumis et qu'il est valide => on traite le formulaire
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            return $this->processForm($offer, 5);
        }
        return $this->render('@SadioJobsPlateform/Default/new.html.twig', ['form' => $form->createView()]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Update Post - Route: /platform/edit-offer/{id} --------------------------------------------------------------------------
    public function editAction($slug, Request $request, SessionInterface $session) {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneWithAllRelations($slug);
        $form  = $this->createForm(OfferEditType::class, $offer);

        // Si le formulaire a été soumis et qu'il est valide => on traite le formulaire
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Si aucun fichier n'a été posté mais que l'attribut url n'est pas vide => 
            // Cas où l'user a mis à jour l'offre en supprimant l'ancienne PJ => on suuprime Attachment de Offer
            if ($offer->getAttachment()->getFile() == null && $offer->getAttachment()->getUrl() !== null) {
                // On recupère l'id de l'Attachment à Supprimer
                $em           = $this->getDoctrine()->getManager();
                $attachmentId = $offer->getAttachment()->getId();
                
                // On save la mise à jour de l'offre (sans l'attachment)
                $offer->setAttachment(null);
                $em->persist($offer);
                
                // On supprime l'ancien attachment de la BDD et du dossier assets/docs (via postDelete() de Attachment)
                $em->remove($em->find(Attachment::class, $attachmentId));
                $em->flush();

                // On save le message Flash et on redirige
                $this->addFlash('notice', 'The offer has been saved!');
                return $this->redirectToRoute('sadioJobsP_singlePost', ['slug' => $offer->getSlug()]);
            }
            return $this->processForm($offer, 5);
        }
        return $this->render('@SadioJobsPlateform/Default/edit.html.twig', ['form' => $form->createView()]);
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
    public function processForm($offer, $userId) {
        // Link Offer to User, then Save Form + Flash and redirect
        $offer->setUser($this->getDoctrine()->getManager()->find(User::class, $userId));
        $this->getDoctrine()->getManager()->persist($offer);
        $this->getDoctrine()->getManager()->flush();
        
        $this->addFlash('notice', 'The offer has been saved!');
        return $this->redirectToRoute('sadioJobsP_singlePost', ['slug' => $offer->getSlug()]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Affiche les 3 derniers post sur le template parent base.html ------------------------------------------------------------------------
    public function recentlyPostedAction() {
        $recentlyPosted = $this->getDoctrine()->getRepository(Offer::class)->findBy([], ['id' => 'DESC'], 3);
        
        return $this->render('@SadioJobsPlateform/Default/platform_recentlyPosted.html.twig', ['recentlyPosted' => $recentlyPosted]);
    }// -----------------------------------------------------------------------------------------------------------------------------
}
