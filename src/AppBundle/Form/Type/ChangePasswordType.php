<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChangePasswordType.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', 'password', ['label' => 'change_password.label.password'])
            ->add('newPassword', 'repeated', [
                'type' => 'password',
                'first_options' => [
                    'label' => 'change_password.label.new_password_first',
                ],
                'second_options' => [
                    'label' => 'change_password.label.new_password_second',
                ],
            ])
            ->add('submit', 'submit', ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Model\\ChangePassword',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_change_password';
    }
}
