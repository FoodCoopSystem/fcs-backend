<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
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
            ->setName('codifico:user:create-user')
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $user = new User($username, $password);

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
