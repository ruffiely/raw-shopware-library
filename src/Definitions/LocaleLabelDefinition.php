<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary\Definitions;

class LocaleLabelDefinition
{
    public function __construct(
        private readonly string $key,private readonly string $translation
    ) {}

    public function getKey(): string
    {
        return $this->key;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }
}
