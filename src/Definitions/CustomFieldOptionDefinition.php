<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary\Definitions;

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

    public static function fromArray(array $config): self
    {
        CustomFieldSetDefinition::assertRequired($config, ['value', 'label']);

        $newSelf = new self();
        $newSelf->value = $config['value'];

        $translations = [];
        foreach ($config['label'] as $key => $value) {
            $translations[] = new LocaleLabelDefinition($key, $value);
        }
        $newSelf->label = $translations;

        return $newSelf;
    }
}
