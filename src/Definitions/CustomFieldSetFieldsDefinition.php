<?php declare(strict_types=1);

namespace RawShopwareLibrary\Definitions;

class CustomFieldSetFieldsDefinition
{
    private string $name;

    private string $type;

    private bool $allowCustomerWrite;

    private bool $storeApiAware;

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
}
