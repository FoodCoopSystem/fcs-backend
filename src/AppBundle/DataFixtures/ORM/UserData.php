<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserData implements FixtureInterface, ContainerAwareInterface
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
        $user = new User('admin', 'admin');
        $user->setFirstName('Marcin');
        $user->setLastName('Dryka');
        $user->setEmail('marcin@dryka.pl');
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_API']);

        $manager->persist($user);
        $manager->flush();
    }
}
