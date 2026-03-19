<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary\Definitions;

class CustomFieldSetFieldsConfigDefinition
{
    /**
     * @var LocaleLabelDefinition[]
     */
    private array $label;

    private int $customFieldPosition = 1;

    /**
     * @var CustomFieldOptionDefinition[]
     */
    private array $options = [];

    private ?string $componentName = null;

    private ?string $customFieldType = null;

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

    public static function fromArray(array $config): CustomFieldSetFieldsConfigDefinition
    {
        CustomFieldSetDefinition::assertRequired($config, ['label']);

        $newSelf = new self();
        $translations = [];
        foreach ($config['label'] as $key => $value) {
            $translations[] = new LocaleLabelDefinition($key, $value);
        }
        $newSelf->label = $translations;
        if (isset($config['options'])) {
            $options = [];
            foreach ($config['options'] as $customFieldOption) {
                $options[] = CustomFieldOptionDefinition::fromArray($customFieldOption);
            }
            $newSelf->options = $options;
        }

        if (isset($config['customFieldPosition'])) {
            $newSelf->customFieldPosition = $config['customFieldPosition'];
        }
        if (isset($config['componentName'])) {
            $newSelf->componentName = $config['componentName'];
        }
        if (isset($config['customFieldType'])) {
            $newSelf->customFieldType = $config['customFieldType'];
        }

        return $newSelf;
    }


}
