<?php declare(strict_types=1);

namespace RawShopwareLibrary\Definitions;

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
    private array $relations;

    /**
     * @var CustomFieldSetFieldsDefinition[]
     */
    private array $customFieldSetFieldsDefinition;

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
}
