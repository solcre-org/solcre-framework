<?php

namespace Solcre\SolcreFramework2\Filter;

use Zend\Hydrator\FilterEnabledInterface;
use ZF\Hal\Plugin\Hal;

class FieldsFilterService implements FilterInterface
{
    public const FILTER_PARAMETER = 'fields';
    public const FILTER_NAME = 'fields.filter';
    public const QUERY_FIELDS_SPLITTER = ',';
    public const FILTER_FIXED_FIELDS = ['id'];
    protected $halPlugin;
    protected $options;

    public function __construct(Hal $halPlugin)
    {
        $this->halPlugin = $halPlugin;
    }

    public function canFilter($options): bool
    {
        return (array_key_exists(self::FILTER_PARAMETER, $options) && ! empty($options[self::FILTER_PARAMETER]));
    }

    public function filter($entity, $fields = null): void
    {
        //@@TODO: support second level filter
        if (! empty($fields)) {
            $this->setOptions($fields);
        }

        if (\is_array($entity)) {
            $entity = array_pop($entity);
        }

        if (! \is_object($entity)) {
            return;
        }

        $this->removeFilter($entity);
        $hydrator = $this->halPlugin->getHydratorForEntity($entity);

        if (empty($hydrator) || ! ($hydrator instanceof FilterEnabledInterface)) {
            return;
        }

        $fields = $this->getOptions();
        $fixedFields = self::FILTER_FIXED_FIELDS;

        $hydrator->addFilter(
            self::FILTER_NAME,
            static function ($fieldName) use ($fields, $fixedFields) {
                return empty($fields) || ! \is_array($fields) || ! count($fields) || (bool)in_array($fieldName, $fields, true) || (bool)in_array($fieldName, $fixedFields, true);
            }
        );
    }

    public function removeFilter($entity): void
    {
        if (\is_array($entity)) {
            $entity = array_pop($entity);
        }

        $hydrator = $this->halPlugin->getHydratorForEntity($entity);
        if (! empty($hydrator)) {
            $hydrator->removeFilter(self::FILTER_NAME);
        }
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options): void
    {
        if (\is_string($options)) {
            $options = explode(self::QUERY_FIELDS_SPLITTER, $options);
        }

        $this->options = $options;
    }

    public function getName(): string
    {
        return self::FILTER_NAME;
    }

    public function prepareOptions($options): void
    {
        if (array_key_exists(self::FILTER_PARAMETER, $options)) {
            $this->setOptions($options[self::FILTER_PARAMETER]);
        }
    }
}
