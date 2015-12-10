<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Type\AuthorizationGrantedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AuthorizationGrantedCollectionType.
 */
class AuthorizationGrantedCollectionType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authorizations', CollectionType::class, ['entry_type' => AuthorizationGrantedType::class])
        ;

        if (!$options['disabled']) {
            $builder->add('submit', SubmitType::class, ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Model\\AuthorizationGroup',
            'disabled' => false,
        ]);
    }
}
