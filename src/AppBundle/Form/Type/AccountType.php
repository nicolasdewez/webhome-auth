<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('birthDate', BirthdayType::class, ['label' => 'users.label.birth_date', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'users.label.email', 'required' => false])
            ->add('locale', ChoiceType::class, ['choices' => ['fr' => 'locales.fr', 'en' => 'locales.en'], 'label' => 'users.label.locale'])
            ->add('submit', SubmitType::class, ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']])
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
}
