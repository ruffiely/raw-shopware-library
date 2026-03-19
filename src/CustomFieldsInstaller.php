<?php declare(strict_types=1);

namespace Raw\ShopwareLibrary;

use Raw\ShopwareLibrary\Definitions\CustomFieldSetDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class CustomFieldsInstaller
{
    /**
     * @param Context $context
     * @param EntityRepository $customFieldSetRepository
     * @param CustomFieldSetDefinition[] $customFieldSetDefinitions
     * @return void
     */
    public function uninstall(
        Context $context,
        EntityRepository $customFieldSetRepository,
        array $customFieldSetDefinitions
    ): void
    {
        // uninstall FieldSet will remove Customfields by Relation
        foreach ($customFieldSetDefinitions as $customFieldSetDefinition) {
            $customFieldSetRepository->delete(
                array_map(
                    fn($id) => ['id' => $id],
                    $this->getCustomFieldSetIdIfExists(
                        $context,
                        $customFieldSetRepository,
                        $customFieldSetDefinition->getName()
                    )
                ),
                $context
            );
        }
    }


    public function activate(
        Context $context,
        EntityRepository $customFieldSetRepository,
        EntityRepository $customFieldSetRelationRepository,
        array $customFieldSetDefinitions
    ): void
    {
        // Add relations
        foreach ($customFieldSetDefinitions as $customFieldSetDefinition) {
            foreach ($customFieldSetDefinition->getRelations() as $relation) {
                $this->addRelation(
                    $context,
                    $customFieldSetRepository,
                    $customFieldSetRelationRepository,
                    $customFieldSetDefinition,
                    $relation
                );
            }
        }
    }

    public function deactivate(
        Context $context,
        EntityRepository $customFieldSetRepository,
        EntityRepository $customFieldSetRelationRepository,
        array $customFieldSetDefinitions
    ): void
    {
        // Remove relations
        foreach ($customFieldSetDefinitions as $customFieldSetDefinition) {
            foreach ($customFieldSetDefinition->getRelations() as $relation) {
                $this->removeRelation();
            }
        }
    }

    /**
     * @param Context $context
     * @param EntityRepository $customFieldSetRepository
     * @param EntityRepository $customFieldRepository
     * @param array $customFieldSetDefinitions
     * @return void
     */
    public function upsert(
        Context $context,
        EntityRepository $customFieldSetRepository,
        EntityRepository $customFieldRepository,
        array $customFieldSetDefinitions
    ): void
    {
        foreach ($customFieldSetDefinitions as $customFieldSetDefinition) {
            $customFieldSetId = $this->getCustomFieldSetIdIfExists($context, $customFieldSetRepository, $customFieldSetDefinition->getName())[0] ?? null;
            if ($customFieldSetId === null) {
                // install Fieldset first if not exists
                $this->installFieldset($context, $customFieldSetRepository, $customFieldSetDefinition);
            }
            $this->upsertCustomFields($context, $customFieldSetRepository, $customFieldRepository, $customFieldSetDefinition);
        }
    }

    private function removeRelation(
        Context $context,
        EntityRepository $customFieldSetRepository,
        EntityRepository $customFieldSetRelationRepository,
        CustomFieldSetDefinition $customFieldSetDefinition
    ): void
    {
        $customFieldSetId = $this->getCustomFieldSetIdIfExists($context, $customFieldSetRepository, $customFieldSetDefinition->getName())[0] ?? null;
        if ($customFieldSetId === null) {
            return;
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customFieldSetId', $customFieldSetId));

        $ids = $customFieldSetRelationRepository->searchIds($criteria, $context)->getIds();
        $customFieldSetRelationRepository->delete($ids, $context);
    }

    private function addRelation(
        Context $context,
        EntityRepository $customFieldSetRepository,
        EntityRepository $customFieldSetRelationRepository,
        CustomFieldSetDefinition $customFieldSetDefinition,
        string $entity
    ): void
    {
        $customFieldSetId = $this->getCustomFieldSetIdIfExists($context, $customFieldSetRepository, $customFieldSetDefinition->getName())[0] ?? null;
        if ($customFieldSetId === null) {
            return;
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customFieldSetId', $customFieldSetId));

        $relation = $customFieldSetRelationRepository->search($criteria, $context)->first();
        if ($relation === null) {
            $customFieldSetRelationRepository->upsert([
                [
                    'customFieldSetId' => $customFieldSetId,
                    'entityName' => $entity
                ]
            ], $context);
        }
    }

    private function upsertCustomFields(
        Context $context,
        EntityRepository $customFieldSetRepository,
        EntityRepository $customFieldRepository,
        CustomFieldSetDefinition $customFieldSetDefinition
    ): void
    {
        $customFieldSetId = $this->getCustomFieldSetIdIfExists($context, $customFieldSetRepository, $customFieldSetDefinition->getName())[0] ?? null;
        if ($customFieldSetId === null) {
            // Should exists at this point, if not break
            return;
        }

        $existingCustomFieldIds = $this->getExistingCustomFieldIds($context, $customFieldRepository, $customFieldSetDefinition->getName());
        $upsertFields = [];

        foreach ($customFieldSetDefinition->getCustomFieldSetFieldsDefinition() as $customFieldSetFieldDefinition) {
            $customFieldSetFieldDefinition->setCustomFieldSetId($customFieldSetId);
            $data = $customFieldSetFieldDefinition->toArray();

            // check if custom field already exists
            if (isset($existingCustomFieldIds[$customFieldSetFieldDefinition->getName()])) {
                $data['id'] = $existingCustomFieldIds[$customFieldSetFieldDefinition->getName()];
            }

            $upsertFields[] = $data;
        }

        $customFieldRepository->upsert($upsertFields, $context);
    }

    private function installFieldset(
        Context $context,
        EntityRepository $customFieldSetRepository,
        CustomFieldSetDefinition $customFieldSetDefinition
    ): void
    {
        $customFieldSetRepository->upsert([
            $customFieldSetDefinition->toArray()
        ], $context);
    }

    /**
     * @param Context $context
     * @param EntityRepository $customFieldSetRepository
     * @param string $name
     * @return array
     */
    private function getCustomFieldSetIdIfExists(Context $context, EntityRepository $customFieldSetRepository, string $name): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $customFieldSetRepository->searchIds($criteria, $context)->getIds();
    }

    /**
     * @param Context $context
     * @param EntityRepository $customFieldRepository
     * @param string $name
     * @return array
     */
    private function getExistingCustomFieldIds(Context $context, EntityRepository $customFieldRepository, string $name): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customFieldSet.name', $name));
        $result = $customFieldRepository->search($criteria, $context);

        $map = [];
        foreach ($result as $customField) {
            $map[$customField->getName()] = $customField->getId();
        }

        return $map; // [name => id]
    }
}