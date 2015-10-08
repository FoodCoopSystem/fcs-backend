<?php

namespace FoodCoopBundle\Behat\Context;

use FoodCoopBundle\Entity\Basket;
use FoodCoopBundle\Entity\Order;
use FoodCoopBundle\Entity\OrderItem;
use FoodCoopBundle\Entity\Supplier;
use FoodCoopBundle\Entity\Product;
use FoodCoopBundle\Entity\User;
use FoodCoopBundle\Request\Criteria;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Codifico\ParameterBagExtension\Context\ParameterBagDictionary;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class DatabaseContext implements Context, KernelAwareContext
{
    use KernelDictionary;
    use ParameterBagDictionary;

    /**
     * @BeforeScenario @database
     */
    public function cleanDatabase()
    {
        $purger = new ORMPurger($this->getDoctrine()->getManager());
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();
    }

    /**
     * @Given /^User "([^"]*)" exists with:$/
     */
    public function userExistsWith($username, TableNode $table)
    {

        $entity = new User($username, 'password');
        foreach ($table->getColumnsHash() as $row) {
            $value = $row['Value'];

            if ('Roles' === $row['Property']) {
                $roles = $entity->getRoles();
                if (!in_array($value, $roles)) {
                    $roles[] = $value;
                }
                $entity->setRoles($roles);
                continue;
            }

            $setter = 'set'.$row['Property'];
            $entity->{$setter}($value);
        }

        $this->getParameterBag()->set('user', $entity);
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();

    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    private function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Given /^supplier "([^"]*)" exists$/
     */
    public function supplierExists($name)
    {
        $supplier = new Supplier($name);
        $this->getEntityManager()->persist($supplier);
        $this->getEntityManager()->flush();

        $this->getParameterBag()->set('supplier', $supplier);

        return $supplier;
    }

    /**
     * @Given /^supplier "([^"]*)" exists with product:$/
     */
    public function supplierExistsWithProduct($name, TableNode $table)
    {
        $data = $table->getRowsHash();
        $supplier = $this->supplierExists($name);

        $product = new Product($data['Name'], (float)$data['Price'], $supplier);
        if (isset($data['Description'])) {
            $product->setDescription($data['Description']);
        }
var_dump($product->getPrice());
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();

        $this->getParameterBag()->set('product', $product);

    }

    /**
     * @Then /^product should not exists$/
     */
    public function productShouldNotExists()
    {
        $product = $this->getParameterBag()->get('product');
        $repository = $this->getEntityManager()->getRepository('FoodCoopBundle:Product');
        $product = $repository->findByCriteria(new Criteria(['id' => $product->getId()]));
        if ($product) {
            throw new \Exception('Product exists');
        }
    }

    /**
     * @Given /^supplier should not exists$/
     */
    public function supplierShouldNotExists()
    {
        $supplier = $this->getParameterBag()->get('supplier');
        $repository = $this->getEntityManager()->getRepository('FoodCoopBundle:Supplier');
        $supplier = $repository->findByCriteria(new Criteria(['id' => $supplier->getId()]));
        if ($supplier) {
            throw new \Exception('Supplier exists');
        }
    }

    /**
     * @Given /^order on "([^"]*)" exists$/
     */
    public function orderOnExists($date, $active = false)
    {
        $order = new Order(new \DateTime($date));
        $order->setActive($active);
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();

        $this->getParameterBag()->set('order', $order);

        return $order;
    }

    /**
     * @Given /^order should not exists$/
     */
    public function orderShouldNotExists()
    {
        $order = $this->getParameterBag()->get('order');
        $repository = $this->getEntityManager()->getRepository('FoodCoopBundle:Order');
        $order = $repository->findByCriteria(new Criteria(['id' => $order->getId()]));

        if ($order) {
            throw new \Exception('Order exists');
        }
    }

    /**
     * @Given /^order should be active$/
     */
    public function orderShouldBeActive()
    {
        $order = $this->getParameterBag()->get('order');
        $this->getEntityManager()->refresh($order);

        /** @var Order $order */
        if (!$order->isActive()) {
            throw new \Exception('Order is not active');
        }
    }

    /**
     * @Given /^active order on "([^"]*)" exists$/
     */
    public function activeOrderOnExists($date)
    {
        $this->orderOnExists($date, $active = true);
    }

    /**
     * @Given /^in order there is item with (\d+) products$/
     */
    public function inOrderThereIsItemWithProducts($quantity)
    {
        $order = $this->getParameterBag()->get('order');
        $product = $this->getParameterBag()->get('product');
        $owner = $this->getParameterBag()->get('user');
        $basket = new Basket();
        $basket->setProduct($product);
        $basket->setOwner($owner);
        $basket->setQuantity($quantity);

        $orderItem = OrderItem::createFromBasket($basket, $order);
        $this->getEntityManager()->persist($orderItem);
        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^basket item with "([^"]*)" products exists$/
     */
    public function basketItemWithProductsExists($quantity)
    {
        $product = $this->getParameterBag()->get('product');
        $user = $this->getParameterBag()->get('user');
        $basketItem = new Basket();
        $basketItem->setProduct($product);
        $basketItem->setQuantity($quantity);
        $basketItem->setOwner($user);
        $this->getEntityManager()->persist($basketItem);
        $this->getEntityManager()->flush();
        $this->getParameterBag()->set('basket', $basketItem);
    }

    /**
     * @Given /^basket should not exists$/
     */
    public function basketShouldNotExists()
    {
        $basket = $this->getParameterBag()->get('basket');
        $repository = $this->getEntityManager()->getRepository('FoodCoopBundle:Basket');
        $basket = $repository->findByCriteria(new Criteria(['id' => $basket->getId()]));
        if ($basket) {
            throw new \Exception('Basket exists');
        }
    }
}
