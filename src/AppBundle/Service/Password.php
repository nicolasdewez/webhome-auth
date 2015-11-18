<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Exception\PasswordException;
use AppBundle\Model\Password as PasswordModel;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Ndewez\WebHome\CommonBundle\Service\Validator;
use Ndewez\WebHome\AuthApiBundle\V0\Model\ChangePassword;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class Password.
 */
class Password
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var Doctrine */
    private $doctrine;

    /** @var Validator */
    private $validator;

    /**
     * @param Doctrine                $doctrine
     * @param EncoderFactoryInterface $encoderFactory
     * @param Validator               $validator
     */
    public function __construct(Doctrine $doctrine, EncoderFactoryInterface $encoderFactory, Validator $validator)
    {
        $this->encoderFactory = $encoderFactory;
        $this->doctrine = $doctrine;
        $this->validator = $validator;
    }

    /**
     * @param User           $user
     * @param ChangePassword $changePassword
     *
     * @throws PasswordException
     */
    private function checkChange(User $user, ChangePassword $changePassword)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        $model = new PasswordModel();
        $model->setValue($changePassword->getNewPassword())
            ->setRepeat($changePassword->getRepeatPassword())
            ->setCurrent($encoder->encodePassword($changePassword->getOldPassword(), $user->getSalt()))
            ->setCurrentSaved($user->getPassword());

        $errors = $this->validator->validateToArray($model);
        if (count($errors)) {
            throw new PasswordException($errors);
        }
    }

    /**
     * @param User           $user
     * @param ChangePassword $changePassword
     *
     * @throws PasswordException
     */
    public function change(User $user, ChangePassword $changePassword)
    {
        $this->checkChange($user, $changePassword);

        $encoder = $this->encoderFactory->getEncoder($user);
        $user->initSalt();
        $user->setPassword($encoder->encodePassword($changePassword->getNewPassword(), $user->getSalt()));

        $this->doctrine->getManager()->flush();
    }
}
