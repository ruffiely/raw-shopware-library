<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary\Definitions;

class CustomFieldSetDefinition
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var CustomFieldSetConfigDefinition
     */
    private CustomFieldSetConfigDefinition $config;

    /**
     * @var CustomFieldSetRelationDefinition[]
     */
    private array $relations = [];

    /**
     * @var CustomFieldSetFieldsDefinition[]
     */
    private array $customFieldSetFieldsDefinition = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function getCustomFieldSetFieldsDefinition(): array
    {
        return $this->customFieldSetFieldsDefinition;
    }

    public function getRelations(): array
    {
        $relations = [];
        foreach ($this->relations as $relation) {
            $relations[] = $relation->getEntity();
        }

        return $relations;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'config' => $this->config->toArray()
        ];
    }

    public static function fromArray(array $config): CustomFieldSetDefinition
    {
        self::assertRequired($config, ['name', 'config', 'customFields']);

        $newSelf = new self();
        $newSelf->name = $config['name'];
        $newSelf->config = CustomFieldSetConfigDefinition::fromArray($config['config']);

        $relations = [];
        foreach ($config['relations'] as $relation) {
            $relations[] = new CustomFieldSetRelationDefinition($relation);
        }
        $newSelf->relations = $relations;

        $customFields = [];
        foreach ($config['customFields'] as $customField) {
            $customFields[] = CustomFieldSetFieldsDefinition::fromArray($customField);
        }
        $newSelf->customFieldSetFieldsDefinition = $customFields;

        return $newSelf;
    }

    public static function assertRequired(array $config, array $requiredFields): void
    {
        foreach ($requiredFields as $requiredField) {
            if (!array_key_exists($requiredField, $config)) {
                throw new \InvalidArgumentException(
                    sprintf('Missing required field "%s" in CustomField definition', $requiredField)
                );
            }
        }
    }
}
