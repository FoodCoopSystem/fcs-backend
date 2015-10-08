<?php

namespace FoodCoopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OAuthData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRandomId('5jjkrf2gnzc4ogsswkc0wwkgk480kwo440o84os0888g8so0sk');
        $client->setSecret('1n72vehqn21w004ss448c48w8o8oko8o4w00oo0w80wgow0o8s');
        $client->setRedirectUris(['http://example.com']);
        $client->setAllowedGrantTypes(array('token', 'authorization_code', 'password'));
        $clientManager->updateClient($client);
    }
}
