<?php

namespace OAuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LoginType.
 */
class LoginType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', 'text', ['label' => 'login.form.username'])
            ->add('_password', 'password', ['label' => 'login.form.password'])
            ->add('_remember_me', 'checkbox', ['required' => false, 'label' => 'login.form.remember'])
            ->add('submit', 'submit', ['label' => 'login.form.submit', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'login';
    }
}
