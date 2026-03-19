<?php declare(strict_types=1);

namespace RawShopwareLibrary\Definitions;

class CustomFieldSetRelationDefinition
{
    private string $entity;

    public function getEntity(): string
    {
        return $this->entity;
    }
}
