<?php

namespace Solcre\SolcreFramework2\Filter;

use Zend\Hydrator\FilterEnabledInterface;
use ZF\Hal\Plugin\Hal;

class FieldsFilterService implements FilterInterface
{
    const FILTER_PARAMETER = 'fields';
    const FILTER_NAME = 'fields.filter';
    const QUERY_FIELDS_SPLITTER = ',';
    public static $FILTER_FIXED_FIELDS = ["id"];
    protected $halPlugin;
    protected $options;
    protected $hasFilters;

    public function __construct(Hal $halPlugin)
    {
        $this->halPlugin = $halPlugin;
        $this->hasFilters = [];
    }

    public function canFilter($options)
    {
        return (array_key_exists(self::FILTER_PARAMETER, $options) && ! empty($options[self::FILTER_PARAMETER]));
    }

    public function filter($entity, $fields = null)
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
        $fixedFields = self::$FILTER_FIXED_FIELDS;

        $hydrator->addFilter(
            self::FILTER_NAME,
            function ($fieldName) use ($fields, $fixedFields) {
                if (empty($fields) || ! \is_array($fields) || ! count($fields) || ! (array_search($fieldName, $fields) === false) || ! (array_search($fieldName, $fixedFields) === false)
                ) {
                    return true;
                }
                return false;
            }
        );
    }

    public function removeFilter($entity)
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

    public function setOptions($options)
    {
        if (\is_string($options)) {
            $options = explode(self::QUERY_FIELDS_SPLITTER, $options);
        }

        $this->options = $options;
    }

    public function getName()
    {
        return self::FILTER_NAME;
    }

    public function prepareOptions($options)
    {
        if (array_key_exists(self::FILTER_PARAMETER, $options)) {
            $this->setOptions($options[self::FILTER_PARAMETER]);
        }
    }
}
