<?php
// Cette Classe permet de construire un formulaire de type Offer. Elle sera appelée depuis un controller
namespace Sadio\JobsPlateformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OfferType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position'   , TextType::class)
                ->add('description', TextareaType::class)
                ->add('categories' , EntityType::class, ['class'        => 'SadioJobsPlateformBundle:Category',
                                                         'choice_label' => 'name',
                                                         'multiple'     =>  true]);
    }
    /**
     * Permet a Doctrine de savoir que cette classe est liée à l'entité Offer
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sadio\JobsPlateformBundle\Entity\Offer'
        ));
    }
}
