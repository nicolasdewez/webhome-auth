<?php

namespace OAuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('_username', TextType::class, ['label' => 'login.form.username'])
            ->add('_password', PasswordType::class, ['label' => 'login.form.password'])
            ->add('_remember_me', CheckboxType::class, ['required' => false, 'label' => 'login.form.remember'])
            ->add('submit', SubmitType::class, ['label' => 'login.form.submit', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }
}
