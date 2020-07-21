<?php

namespace Solcre\SolcreFramework2\Hydrator;

use Doctrine\Laminas\Hydrator\DoctrineObject;
use ReflectionClass;
use ReflectionProperty;
use function in_array;

class EntityHydrator extends DoctrineObject
{
    protected function extractByValue($object): array
    {
        $data = parent::extractByValue($object);
        $entityFields = array_merge($this->metadata->getFieldNames(), $this->metadata->getAssociationNames());
        $fieldNames = $this->getIgnoredProperties($object, $entityFields);

        $methods = get_class_methods($object);
        foreach ($fieldNames as $fieldName) {
            $getter = 'get' . \str_replace([' ', '_', '-'], '', \ucwords($fieldName, ' _-'));
            $dataFieldName = $this->computeExtractFieldName($fieldName);

            if (in_array($getter, $methods, true)) {
                $data[$dataFieldName] = $this->extractValue($fieldName, $object->$getter(), $object);
            }
        }
        return $data;
    }

    private function getIgnoredProperties($object, $entityFields)
    {
        $reflect = new ReflectionClass($object);
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        $propertiesArray = [];
        foreach ($properties as $property) {
            $propertiesArray[] = $property->name;
        }
        return array_diff($propertiesArray, $entityFields);
    }
}
