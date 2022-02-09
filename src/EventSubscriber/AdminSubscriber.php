<?php

namespace App\EventSubscriber;

use App\Entity\Entity;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ["setCreatedAt"],
            BeforeEntityUpdatedEvent::class => ["setUpdatedAt"]
        ];
    }

    public function setCreatedAt(BeforeEntityPersistedEvent $event) : void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Entity){
            return;
        }

        $entity->setCreatedAt(new DateTimeImmutable());
    }

    public function setUpdatedAt(BeforeEntityUpdatedEvent $event) : void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Entity){
            return;
        }

        $entity->setUpdatedAt(new DateTimeImmutable());
    }
}
