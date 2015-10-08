<?php

namespace FoodCoopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Basket
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FoodCoopBundle\Entity\BasketRepository")
 */
class Basket
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
    private $quantity = 0;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="basket")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var ArrayCollection|Product[]
     *
     * @ORM\OneToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Basket
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product[]|ArrayCollection $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function increaseQuantityBy($quantity)
    {
        $this->quantity += (int)$quantity;
    }

    public function isOwnedBy(UserInterface $user)
    {
        return $this->owner->equals($user);
    }
}
