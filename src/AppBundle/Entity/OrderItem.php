<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\OrderItemRepository")
 */
class OrderItem
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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orderItems")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var Product
     *
     * @ORM\OneToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    private function __construct(Product $product, $quantity, User $owner, Order $order)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->owner = $owner;
        $this->order = $order;
    }

    public static function createFromBasket(Basket $item, Order $order)
    {
        return new OrderItem($item->getProduct(), $item->getQuantity(), $item->getOwner(), $order);
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }
}

