<?php

declare(strict_types=1);

namespace EonX\EasyDoctrine\Events;

final class EntityCreatedEvent implements EntityActionEventInterface
{
    /**
     * @param array<string, mixed> $changeSet
     */
    public function __construct(
        private object $entity,
        private array $changeSet,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getChangeSet(): array
    {
        return $this->changeSet;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
