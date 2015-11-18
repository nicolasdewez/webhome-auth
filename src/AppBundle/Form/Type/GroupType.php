<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GroupType.
 */
class GroupType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', null, ['label' => 'groups.label.code'])
            ->add('title', null, ['label' => 'groups.label.title'])
            ->add('active', null, ['required' => false, 'label' => 'groups.label.active'])
        ;

        if ($options['submit']) {
            $builder->add('submit', 'submit', ['label' => 'actions.submit', 'attr' => ['class' => 'btn btn-primary']]);
        }

        if ($options['delete']) {
            $builder->add('delete', 'submit', ['label' => 'actions.delete', 'validation_groups' => false, 'attr' => ['class' => 'btn btn-danger']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['delete', 'submit']);
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Entity\\Group',
            'delete' => false,
            'submit' => true,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'group';
    }
}
