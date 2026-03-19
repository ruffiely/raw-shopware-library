<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary\Definitions;

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

        return ['label' => $array];
    }

    /**
     * @param array $config
     * @return CustomFieldSetConfigDefinition
     */
    public static function fromArray(array $config): CustomFieldSetConfigDefinition
    {
        CustomFieldSetDefinition::assertRequired($config, ['label']);

        $newSelf = new self();
        $translations = [];
        foreach ($config['label'] as $key => $value) {
            $translations[] = new LocaleLabelDefinition($key, $value);
        }
        $newSelf->label = $translations;

        return $newSelf;
    }
}
