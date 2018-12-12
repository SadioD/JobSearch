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
use Sadio\JobsPlateformBundle\Services\Purge;
// Acces Role Control
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        if ($page > $numberOfPages && count($list) > 0) {
            throw $this->createNotFoundException('Page "'.$page.'" does not exist.');
        }
        // On affiche la vue  en usant de la méthode longue --> vue dans Sadio/JobsPlateform/Ressources/views/Default/views/default/index.html.twig
        return $this->render('@SadioJobsPlateform/Default/index.html.twig', ['list'          => $list,
                                                                             'page'          => $page,
                                                                             'numberOfPages' => $numberOfPages]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Single Post - Route: /platform/offer/{offerSlug} --------------------------------------------------------------------------- 
    public function viewAction($offerSlug) {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneWithAllRelations($offerSlug);

        if (!$offer) {
            throw $this->createNotFoundException('No job was found for given information - '. $offerSlug);
        }
        return $this->render('@SadioJobsPlateform/Default/view.html.twig', ['offer' => $offer]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    /**
     * Page Add New Post - Route: /platform/new-offer -------------------------------------------------------------------------------
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function newAction(Request $request) {
        $offer = new Offer();
        $form  = $this->createForm(OfferType::class, $offer);

        // Si le formulaire a été soumis et qu'il est valide => on traite le formulaire
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            return $this->processForm($offer, $this->getUser()->getId());
        }
        return $this->render('@SadioJobsPlateform/Default/new.html.twig', ['form' => $form->createView()]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    /**
     * Page Update Post - Route: /platform/edit-offer/{offerSlug} --------------------------------------------------------------------------
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function editAction($offerSlug, Request $request, Purge $purgator) {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneWithAllRelations($offerSlug);
        
        if (!$offer) {
            throw $this->createNotFoundException('No job was found for given information - '. $offerSlug);
        }
        // On recupère le form et on vérifie s'il a été soumis et qu'il est valide 
        // Si Oui, => on le traite
        $form  = $this->createForm(OfferEditType::class, $offer);
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            // Si l'user a mis à jour l'offre en supprimant l'ancienne PJ => attribut url non vide
            // => On Purge la BDD et le dossier docs pour supprimer la PJ orpheline (vu ue l'User n'en veut plus)
            if ($purgator->purgedOldAttachments($offer)) {
                $this->addFlash('notice', 'The offer has been saved!');
                return $this->redirectToRoute('sadioJobsP_singlePost', ['offerSlug' => $offer->getSlug()]);
            } else {
                return $this->processForm($offer, $this->getUser()->getId());
            }
        }
        return $this->render('@SadioJobsPlateform/Default/edit.html.twig', ['form' => $form->createView()]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Page Delete Post - Route: /platform/delete-offer/{offerId} -----------------------------------------------------------------------
    public function deleteAction($offerId) {
        // Si l'User n'est pas un Admin => Exception
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) 
        {
            throw new AccessDeniedException('Note: Only the Web Master is allowed to delete Offers. Please contact him to do so...');
        }
        // Si l'user est un ADMIN => on supprime l'offre
        $offer = $this->getDoctrine()->getManager()->find(Offer::class, $offerId);

        if (!$offer) {
            throw $this->createNotFoundException('No job was found for given information - '. $offerId);
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
        return $this->redirectToRoute('sadioJobsP_singlePost', ['offerSlug' => $offer->getSlug()]);
    }// -----------------------------------------------------------------------------------------------------------------------------
    // Affiche les 3 derniers post sur le template parent base.html ------------------------------------------------------------------------
    public function recentlyPostedAction() {
        $recentlyPosted = $this->getDoctrine()->getRepository(Offer::class)->findBy([], ['id' => 'DESC'], 3);
        
        return $this->render('@SadioJobsPlateform/Default/platform_recentlyPosted.html.twig', ['recentlyPosted' => $recentlyPosted]);
    }// -----------------------------------------------------------------------------------------------------------------------------
}
