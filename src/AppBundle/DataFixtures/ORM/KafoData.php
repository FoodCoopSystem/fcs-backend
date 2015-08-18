<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Producent;
use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KafoData implements FixtureInterface, ContainerAwareInterface
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
        $producent = new Producent('Kafo');
        $manager->persist($producent);

        $products = [
            ['name' => 'Barazylia Santos (250g)', 'price' => 19.00],
            ['name' => 'Ethiopia (250g)', 'price' => 28.00, 'description' => 'Kawa Jednorodna'],
            ['name' => 'Rwanda (250g)', 'price' => 28.00, 'description' => 'Kawa Jednorodna']
        ];

        foreach ($products as $product) {
            $entity = new Product($product['name'], $product['price'], $producent);

            if (isset($product['description'])) {
                $entity->setDescription($product['description']);
            }

            $manager->persist($entity);
        }

        $manager->flush();
    }
}