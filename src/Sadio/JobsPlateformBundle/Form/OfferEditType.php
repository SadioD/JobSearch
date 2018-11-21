<?php
// Cette Classe permet de construire un formulaire de type OfferEdit (mise à jour des offres)
// Elle hérite de la class OfferType
namespace Sadio\JobsPlateformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // On ne rajoute plus les champs ici, car la méthode getParent() sera d'abord appelée, et celle-ci 
        // retournera la méthode buildForm de OfferType

        /* Par contre on va rajouter les évènements pour nous former un peu. PRE_SET_DATA est déclenché juste
           avant que le formulaire ne soit rempli par des valaurs de $offer (PS: cet évènement ne s'applique pas
           seulement au cas de mise à jour d'offres. Il peut également s'appliquer au cas de new offer => il sera 
           déclenché au même moment. c'ets un peu l'équivalent onInit dans Angular. => OnInit du composant Form) */
        // addEventListener a é arguments : l'évènement et la fonction à exécuter une l'évènement déclenché
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            // $event permet de récupérer l'Entité qui s'apprete à remplir Form
            $offer = $event->getData();

            // Cette condition est essentielle car souvent lorsque PRE_SET_DATA est déclenché la première fois
            // Il contient un objet null
            if ($offer === null) {
                return;
            }
            // A partir d'ici $offer contient les données de l'entité Offer (vide si new-offer ou non si edit)
            // Ex: Je décide de disable le champ position si l'user a pour email agugga@yahoo.dr
            if ($offer->getUser()->getEmail() == 'agugga@yahoo.dr') {
                $event->getForm()->add('position', TextType::class, ['disabled' => true]);
            }
        });
    }
    // Permet d'afficher le formulaire parent (OfferType).  
    public function getParent()
    {
        return OfferType::class;
    }
}
