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
    private $active = false;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(name="execution_at", type="date")
     */
    private $executionAt;

    /**
     * @var OrderItem[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order")
     */
    private $items;

    public function __construct(\DateTime $executionAt)
    {
        $this->items = new ArrayCollection();
        $this->setExecutionAt($executionAt);
    }

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
     * @return \DateTime
     */
    public function getExecutionAt()
    {
        return $this->executionAt;
    }

    /**
     * @param \DateTime $executionAt
     */
    public function setExecutionAt(\DateTime $executionAt)
    {
        $this->executionAt = $executionAt;
    }

    /**
     * @return OrderItem[]|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function hasItems()
    {
        return $this->items->count() > 0;
    }
}
