<?php declare(strict_types=1);

namespace RawShopwareLibrary\Definitions;

class LocaleLabelDefinition
{
    private string $key;

    private string $translation;

    public function getKey(): string
    {
        return $this->key;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }
}
