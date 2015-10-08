<?php

namespace FoodCoopBundle\Command;

use FoodCoopBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:user:create')
            ->setDescription('Creates a new OAuth client credentials')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username to create'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'User password'
            )
            ->addOption(
                'roles',
                'r',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Additional roles to assign to user'
            )
            ->addOption(
                'firstName',
                'f',
                InputOption::VALUE_OPTIONAL,
                'User first name'
            )
            ->addOption(
                'lastName',
                'l',
                InputOption::VALUE_OPTIONAL,
                'User last name'
            )
            ->addOption(
                'email',
                'm',
                InputOption::VALUE_OPTIONAL,
                'User email'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $user = new User($username, $password);

        $roles = ['ROLE_USER', 'ROLE_API'];
        if ($input->hasOption('roles')) {
            $roles = array_merge($roles, $input->getOption('roles'));
        }
        $user->setRoles($roles);

        if ($input->hasOption('firstName')) {
            $user->setFirstName($input->getOption('firstName'));
        }

        if ($input->hasOption('lastName')) {
            $user->setLastName($input->getOption('lastName'));
        }

        if ($input->hasOption('email')) {
            $user->setEmail($input->getOption('email'));
        }

        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush($user);

        $output->writeln(
            sprintf(
                'New user: <info>%s</info>, with password: <info>%s</info> created',
                $username,
                $password
            )
        );
    }
}
