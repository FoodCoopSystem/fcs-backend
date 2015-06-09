<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('codifico:oauth:create-client')
            ->setDescription('Creates a new OAuth client credentials')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(['http://example.com']);
        $client->setAllowedGrantTypes(array('token', 'authorization_code', 'password'));
        $clientManager->updateClient($client);
        $output->writeln(
            sprintf(
                'New client with public id <info>%s</info>, secret <info>%s</info> created',
                $client->getPublicId(),
                $client->getSecret()
            )
        );
    }
}
