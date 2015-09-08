<?php

namespace AppBundle\Behat\Context;

use AppBundle\Entity\Basket;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Producent;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Request\Criteria;
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
     * @Given /^producent "([^"]*)" exists$/
     */
    public function producentExists($name)
    {
        $producent = new Producent($name);
        $this->getEntityManager()->persist($producent);
        $this->getEntityManager()->flush();

        $this->getParameterBag()->set('producent', $producent);

        return $producent;
    }

    /**
     * @Given /^producent "([^"]*)" exists with product:$/
     */
    public function producentExistsWithProduct($name, TableNode $table)
    {
        $data = $table->getRowsHash();
        $producent = $this->producentExists($name);

        $product = new Product($data['Name'], $data['Price'], $producent);
        if (isset($data['Description'])) {
            $product->setDescription($data['Description']);
        }

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
        $repository = $this->getEntityManager()->getRepository('AppBundle:Product');
        $product = $repository->findByCriteria(new Criteria(['id' => $product->getId()]));
        if ($product) {
            throw new \Exception('Product exists');
        }
    }

    /**
     * @Given /^producent should not exists$/
     */
    public function producentShouldNotExists()
    {
        $producent = $this->getParameterBag()->get('producent');
        $repository = $this->getEntityManager()->getRepository('AppBundle:Producent');
        $producent = $repository->findByCriteria(new Criteria(['id' => $producent->getId()]));
        if ($producent) {
            throw new \Exception('Product exists');
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
        $repository = $this->getEntityManager()->getRepository('AppBundle:Order');
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
}
