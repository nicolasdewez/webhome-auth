<?php

namespace AppBundle\Command;

use AppBundle\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ResetPasswordCommand.
 */
class ResetPasswordCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:password:reset')
            ->setDescription('Reset to user\'s password ')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addOption('admin', 'a', InputOption::VALUE_NONE, 'Affect user in \'Super Administrator\' group')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $user = $manager->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \InvalidArgumentException(sprintf('Username %s is not known.', $username));
        }

        if ($input->getOption('admin')) {
            $group = $manager->getRepository('AppBundle:Group')->findOneBy(['code' => Group::SUPER_ADM]);
            if (!$group) {
                throw new \RuntimeException(sprintf('Group %s is not known.', Group::SUPER_ADM));
            }

            $user->setGroup($group);
        }

        $passwordService = $this->getContainer()->get('app.password');
        $password = $passwordService->generatePassword();
        $passwordService->changePassword($user, $password);

        $output->writeln(sprintf('<info>Username : %s</info>', $username));
        $output->writeln(sprintf('<info>Password: %s</info>', $password));
        $output->writeln(sprintf('<info>Group: %s (%s)</info>', $user->getGroup()->getTitle(), $user->getGroup()->getCode()));
    }
}
