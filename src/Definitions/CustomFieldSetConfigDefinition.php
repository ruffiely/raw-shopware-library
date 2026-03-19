<?php declare(strict_types=1);

namespace RawShopwareLibrary\Definitions;

class CustomFieldSetConfigDefinition
{
    /**
     * @var LocaleLabelDefinition[]
     */
    private array $label;

    public function toArray(): array
    {
        $array = [];

        foreach ($this->label as $labelDefinition) {
            $array[$labelDefinition->getKey()] = $labelDefinition->getTranslation();
        }

        return $array;
    }
}
