<?php declare(strict_types=1);

namespace RawShopwareLibrary\Definitions;

class CustomFieldSetFieldsConfigDefinition
{
    /**
     * @var LocaleLabelDefinition[]
     */
    private array $label;

    private int $customFieldPosition;

    /**
     * @var CustomFieldOptionDefinition[]
     */
    private array $options = [];

    private ?string $componentName;

    private ?string $customFieldType;

    public function toArray(): array
    {
        $translations = [];
        foreach ($this->label as $labelDefinition) {
            $translations[$labelDefinition->getKey()] = $labelDefinition->getTranslation();
        }

        $options = [];
        foreach ($this->options as $customFieldOptionDefinition) {
            $options[] = $customFieldOptionDefinition->toArray();
        }

        $array = [
            'customFieldPosition' => $this->customFieldPosition
        ];

        if (count($translations) > 0) {
            $array['label'] = $translations;
        }

        if (count($options) > 0) {
            $array['options'] = $options;
        }

        if (!is_null($this->componentName)) {
            $array['componentName'] = $this->componentName;
        }

        if (!is_null($this->customFieldType)) {
            $array['customFieldType'] = $this->customFieldType;
        }

        return $array;
    }
}
