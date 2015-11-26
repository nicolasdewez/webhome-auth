<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
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
            ->add('authorizations', 'collection', ['type' => 'app_authorization_granted'])
        ;

        if (!$options['disabled']) {
            $builder->add('submit', 'submit', ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']]);
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

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_authorization_granted_collection';
    }
}
