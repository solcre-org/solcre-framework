<?php

namespace Solcre\SolcreFramework2\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class SearchFilter extends SQLFilter
{

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $sql = '';
        if ($this->hasParameter('query')) {
            $searchTerm = trim($this->getParameter('query'), "'");

            if (! empty($searchTerm)) {
                //Get searchable fields
                $searchableFields = $this->getSearchableFields($targetEntity);

                //Init value false
                $whereApplied = false;

                //Check searchable fields
                if (\is_array($searchableFields) && \count($searchableFields)) {
                    foreach ($searchableFields as $field) {
                        $like = sprintf("%s.%s LIKE '%%%s%%'", $targetTableAlias, $field, $searchTerm);
                        if ($whereApplied) {
                            $sql .= sprintf(' OR %s', $like);
                        } else {
                            //First must be a where others addWhere
                            $whereApplied = true;

                            $sql = $like;
                        }
                    }
                }
            }
        }

        return $sql;
    }

    private function getSearchableFields(ClassMetadata $metaData): array
    {
        $searchableFields = [];

        $fieldMappings = $metaData->fieldMappings;

        //Check field mappings
        if (\is_array($fieldMappings) && \count($fieldMappings)) {
            foreach ($fieldMappings as $key => $field) {

                //Check searchable options
                if (\is_array($field) && \is_array($field['options']) && isset($field['options']['searchable']) && $field['options']['searchable']) {
                    $searchableFields[] = $field['columnName'];
                }
            }
        }
        return $searchableFields;
    }
}

?>