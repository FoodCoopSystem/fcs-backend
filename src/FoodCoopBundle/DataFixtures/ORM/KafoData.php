<?php

namespace FoodCoopBundle\DataFixtures\ORM;

use FoodCoopBundle\Entity\Producent;
use FoodCoopBundle\Entity\Product;
use FoodCoopBundle\Entity\Supplier;
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
        $supplier = new Supplier('Kafo');
        $manager->persist($supplier);

        $products = [
            ['name' => 'Barazylia Santos (250g)', 'price' => 19.00],
            ['name' => 'Ethiopia (250g)', 'price' => 28.00, 'description' => 'Kawa Jednorodna'],
            ['name' => 'Rwanda (250g)', 'price' => 28.00, 'description' => 'Kawa Jednorodna']
        ];

        foreach ($products as $product) {
            $entity = new Product($product['name'], $product['price'], $supplier);

            if (isset($product['description'])) {
                $entity->setDescription($product['description']);
            }

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
