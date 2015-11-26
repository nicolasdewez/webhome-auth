<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\AuthorizationTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AuthorizationGrantedType.
 */
class AuthorizationGrantedType extends AbstractType
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authorization', 'hidden')
            ->add('granted', 'checkbox', ['required' => false])
        ;

        $builder->get('authorization')
            ->addModelTransformer(new AuthorizationTransformer($this->manager));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Model\\AuthorizationGranted',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_authorization_granted';
    }
}
