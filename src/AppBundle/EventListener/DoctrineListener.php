<?php

namespace AppBundle\EventListener;


use AppBundle\Event\EntityCreatedEvent;
use AppBundle\Event\EntityRemoveEvent;
use AppBundle\Event\EntityUpdatedEvent;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineListener
{
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function onEntityCreate(EntityCreatedEvent $event)
    {
        $this->manager->flush();
    }

    public function onEntityUpdate(EntityUpdatedEvent $event)
    {
        $this->manager->flush();
    }

    public function onEntityRemove(EntityRemoveEvent $event)
    {
        $this->manager->flush();
    }
}
