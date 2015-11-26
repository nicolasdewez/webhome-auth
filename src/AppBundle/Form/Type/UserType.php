<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class UserType extends AbstractType
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
            ->add('username', null, ['label' => 'users.label.username'])
            ->add('group', 'entity', [
                'class' => 'AppBundle:Group',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.code', 'ASC');
                },
                'choice_label' => 'title',
                'label' => 'users.label.group',
            ])
        ;

        if (!$options['disabled']) {
            $builder->add('password', 'repeated', [
                'label' => 'users.label.password',
                'type' => 'password',
                'required' => false,
                'first_options' => ['label' => 'users.label.password'],
                'second_options' => ['label' => 'users.label.password_repeated'],
            ]);
        }

        $disabled = false;
        if (!$options['activate']) {
            $disabled = true;
        }

        $builder->add('active', null, ['required' => false, 'label' => 'users.label.active', 'attr' => ['disabled' => $disabled]]);

        if ($options['submit']) {
            $builder->add('submit', 'submit', ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']]);
        }

        if ($options['delete']) {
            $builder->add('delete', 'submit', ['label' => 'actions.delete', 'attr' => ['class' => 'btn btn-danger']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['delete', 'submit', 'activate']);
        $resolver->setAllowedTypes('delete', 'boolean');
        $resolver->setAllowedTypes('submit', 'boolean');
        $resolver->setAllowedTypes('activate', 'boolean');
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Entity\\User',
            'delete' => false,
            'activate' => true,
            'submit' => true,
        ]);
        $resolver->setDefault('disabled', function (Options $options) {
            if (!$options['delete'] && !$options['submit']) {
                return true;
            }

            return false;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_user';
    }
}
