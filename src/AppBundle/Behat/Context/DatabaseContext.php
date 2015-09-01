<?php

namespace AppBundle\Behat\Context;

use AppBundle\Entity\User;
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
    public function existsWith($username, TableNode $table)
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
}