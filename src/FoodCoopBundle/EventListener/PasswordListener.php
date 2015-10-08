<?php

namespace FoodCoopBundle\EventListener;

use FoodCoopBundle\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordListener
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $this->updatePassword($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if ($entity instanceof User) {

            $this->updatePassword($entity);
        }
    }

    private function updatePassword(User $entity)
    {
        if ($entity->getPlainPassword()) {
            $password = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($password);
        }
    }
}