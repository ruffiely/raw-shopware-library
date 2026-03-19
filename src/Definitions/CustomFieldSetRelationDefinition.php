<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary\Definitions;

class CustomFieldSetRelationDefinition
{
    private string $entity;

    public function __construct(string $entity) {
        $this->entity = $entity;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }
}
