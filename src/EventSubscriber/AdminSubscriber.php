<?php

namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Product;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ["setCreatedAt"],
            BeforeEntityUpdatedEvent::class => ["setUpdatedAt"]
        ];
    }

    public function setCreatedAt(BeforeEntityPersistedEvent $event) : void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Product && !$entity instanceof Category){
            return;
        }

        $entity->setCreatedAt(new DateTimeImmutable());
    }

    public function setUpdatedAt(BeforeEntityUpdatedEvent $event) : void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Product && !$entity instanceof Category){
            return;
        }

        $entity->setUpdatedAt(new DateTimeImmutable());
    }
}
