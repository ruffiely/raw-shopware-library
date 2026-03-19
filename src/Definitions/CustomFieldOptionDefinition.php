<?php declare(strict_types=1);

namespace RawShopwareLibrary\Definitions;

class CustomFieldOptionDefinition
{
    private string $value;

    /**
     * @var LocaleLabelDefinition[]
     */
    private array $label;

    public function toArray(): array
    {
        $translations = [];
        foreach ($this->label as $labelDefinition) {
            $translations[$labelDefinition->getKey()] = $labelDefinition->getTranslation();
        }

        return [
            'value' => $this->value,
            'label' => $translations
        ];
    }
}
