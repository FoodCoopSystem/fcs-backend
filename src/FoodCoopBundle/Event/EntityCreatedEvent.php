<?php

namespace FoodCoopBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class EntityCreatedEvent extends Event
{
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
