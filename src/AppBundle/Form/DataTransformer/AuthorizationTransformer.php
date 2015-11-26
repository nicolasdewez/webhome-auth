<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Authorization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class AuthorizationTransformer.
 */
class AuthorizationTransformer implements DataTransformerInterface
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
     * Transforms an object (issue) to a string (number).
     *
     * @param Authorization $authorization
     *
     * @return string
     */
    public function transform($authorization)
    {
        if (null === $authorization) {
            return '';
        }

        return $authorization->getId();
    }

    /**
     * Transforms a string (code) to an object (authorization).
     *
     * @param int $id
     *
     * @return Authorization|null
     *
     * @throws TransformationFailedException if object (authorization) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        $authorization = $this->manager
            ->getRepository('AppBundle:Authorization')
            ->find($id)
        ;

        if (null === $authorization) {
            throw new TransformationFailedException(sprintf('An authorization with identifier "%d" does not exist!', $id));
        }

        return $authorization;
    }
}
