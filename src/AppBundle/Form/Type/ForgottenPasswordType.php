<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ForgottenPasswordType.
 */
class ForgottenPasswordType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, ['label' => 'home.label.forgotten_password.username'])
            ->add('firstName', null, ['label' => 'home.label.forgotten_password.first_name'])
            ->add('lastName', null, ['label' => 'home.label.forgotten_password.last_name'])
            ->add('email', EmailType::class, ['label' => 'home.label.forgotten_password.email'])
            ->add('submit', SubmitType::class, ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Entity\\ForgottenPassword',
        ]);
    }
}
