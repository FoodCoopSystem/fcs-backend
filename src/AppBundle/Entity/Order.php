<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="basket_order")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\OrderRepository")
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(name="execution_at", type="date")
     */
    private $executionAt;

    /**
     * @var Order[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Order", mappedBy="order")
     */
    private $items;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getExecutionAt()
    {
        if ($this->executionAt instanceof \DateTime) {
            $this->executionAt = \DateTimeImmutable::createFromMutable($this->executionAt);
        }
        return $this->executionAt;
    }

    /**
     * @param \DateTimeImmutable $executionAt
     */
    public function setExecutionAt(\DateTimeImmutable $executionAt)
    {
        $this->executionAt = $executionAt;
    }

}
