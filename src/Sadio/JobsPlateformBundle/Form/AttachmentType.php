<?php
// Permet de construire le form d'upload de la PJ. Il sera imbriqué par Offer
namespace Sadio\JobsPlateformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AttachmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Le troisième paramètre correspond aux options du champ (label, required, etc.)
        $builder->add('file', FileType::class, ['label' => 'Upload File (doc|pdf) - Max 200Ko']);
    }
    /**
     * Permet a Doctrine de savoir que cette classe est liée à l'entité Attachment
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sadio\JobsPlateformBundle\Entity\Attachment'
        ));
    }
}
