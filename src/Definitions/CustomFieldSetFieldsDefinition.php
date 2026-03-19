<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary\Definitions;

class CustomFieldSetFieldsDefinition
{
    private string $name;

    private string $type;

    private bool $allowCustomerWrite = false;

    private bool $storeApiAware = false;

    private ?string $customFieldSetId = null;

    private CustomFieldSetFieldsConfigDefinition $config;

    public function setCustomFieldSetId(?string $customFieldSetId): void
    {
        $this->customFieldSetId = $customFieldSetId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'allowCustomerWrite' => $this->allowCustomerWrite,
            'storeApiAware' => $this->storeApiAware,
            'config' => $this->config->toArray(),
            'customFieldSetId' => $this->customFieldSetId,
        ];
    }

    public static function fromArray(array $config): CustomFieldSetFieldsDefinition
    {
        CustomFieldSetDefinition::assertRequired($config, ['name', 'type', 'config']);

        $newSelf = new self();
        $newSelf->name = $config['name'];
        $newSelf->type = $config['type'];
        if (isset($config['allowCustomerWrite'])) {
            $newSelf->allowCustomerWrite = $config['allowCustomerWrite'];
        }
        if (isset($config['storeApiAware'])) {
            $newSelf->storeApiAware = $config['storeApiAware'];
        }
        $newSelf->config = CustomFieldSetFieldsConfigDefinition::fromArray($config['config']);

        return $newSelf;
    }
}
