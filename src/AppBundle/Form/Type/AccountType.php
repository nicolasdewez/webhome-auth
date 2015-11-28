<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AccountType.
 */
class AccountType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, ['label' => 'users.label.first_name'])
            ->add('lastName', null, ['label' => 'users.label.last_name'])
            ->add('birthDate', 'birthday', ['label' => 'users.label.birth_date', 'required' => false])
            ->add('email', 'email', ['label' => 'users.label.email', 'required' => false])
            ->add('locale', 'choice', ['choices' => ['fr' => 'locales.fr', 'en' => 'locales.en'], 'label' => 'users.label.locale'])
            ->add('submit', 'submit', ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Entity\\User',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_account';
    }
}
