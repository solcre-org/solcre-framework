<?php

namespace Solcre\SolcreFramework2\Filter;

use Solcre\SolcreFramework2\Strategy\ExpandEmbeddedStrategy;
use Zend\Hydrator\AbstractHydrator;
use ZF\Hal\Plugin\Hal;

class ExpandFilterService implements FilterInterface
{
    const FILTER_PARAMETER = 'expand';
    const FILTER_NAME = 'expand.filter';
    /**
     *
     * @var Hal
     */
    protected $halPlugin;
    /**
     *
     * @var array
     */
    protected $options;
    /**
     *
     * @var bool
     */
    protected $hasFilters;

    public function __construct(Hal $halPlugin)
    {
        $this->halPlugin = $halPlugin;
        $this->hasFilters = [];
    }

    public function prepareOptions($options)
    {
        if (array_key_exists(self::FILTER_PARAMETER, $options)) {
            $this->setOptions($options[self::FILTER_PARAMETER]);
        }
    }

    public function canFilter($options)
    {
        return array_key_exists(self::FILTER_PARAMETER, $options) && ! empty($options[self::FILTER_PARAMETER]);
    }

    public function getName()
    {
        return self::FILTER_NAME;
    }

    public function filter($entity, $options = null)
    {
        if (! empty($options)) {
            $this->setOptions($options);
        }

        //Control entity
        if (\is_array($entity)) {
            $entity = array_pop($entity);
        }
        if (! \is_object($entity)) {
            return;
        }

        //Init removing filters
        $this->removeFilter($entity);

        $hydrator = $this->halPlugin->getHydratorForEntity($entity);
        /* @var $hydrator AbstractHydrator */
        if (empty($hydrator) || ! ($hydrator instanceof AbstractHydrator)) {
            return;
        }

        //Get options
        $options = $this->getOptions();

        if (\is_array($options) && count($options) > 0) {
            //Create strategies
            foreach ($options as $fieldName => $expand) {
                $strategy = new ExpandEmbeddedStrategy();
                $strategy->setExpand($expand);
                $hydrator->addStrategy($fieldName, $strategy);
            }
        }
    }

    public function removeFilter($entity)
    {
        //Control entity
        if (\is_array($entity)) {
            $entity = array_pop($entity);
        }
        //Get hydrator
        $hydrator = $this->halPlugin->getHydratorForEntity($entity);
        if (! empty($hydrator) && $hydrator instanceof AbstractHydrator) {
            //Get options
            $options = $this->getOptions();

            //Remove strategies
            if (\is_array($options) && count($options) > 0) {
                foreach ($options as $fieldName => $expand) {
                    $hydrator->removeStrategy($fieldName);
                }
            }
        }
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($expand)
    {
        $this->options = $this->processOptions($expand);
    }

    /**
     * Parse expand query string
     *
     * @param string $expand
     *
     * @return array
     */
    protected function processOptions($expand)
    {
        $result = [];
        $optionsResult = [];

        //expand field values
        preg_match_all("/([^,]*?)\((.*?)\)/", $expand, $optionsResult);

        //Control options results
        if (! (\is_array($optionsResult[1]) && count($optionsResult[1]) > 0)
            || ! (\is_array($optionsResult[2]) && count($optionsResult[2]) > 0)
        ) {
            return;
        }

        //Set vars
        $fieldNames = $optionsResult[1];
        $fieldOptions = $optionsResult[2];

        //For each field option
        $count = count($fieldOptions);
        for ($i = 0; $i < $count; $i++) {
            $options = [];

            //Expand field options
            preg_match_all("/([a-zA-Z]*?)\:([0-9a-zA-Z]*)/", $fieldOptions[$i], $options);

            //Control options
            if (! (\is_array($options[1]) && count($options[1]) > 0)
                || ! (\is_array($options[2]) && count($options[2]) > 0)
            ) {
                continue;
            }

            //Set options vars
            $optionNames = $options[1];
            $optionValues = $options[2];

            //Init result var
            $result[$fieldNames[$i]] = [];

            //Parse options with field name
            $count = count($optionValues);
            for ($j = 0; $j < $count; $j++) {
                $result[$fieldNames[$i]][$optionNames[$j]] = $optionValues[$j];
            }
        }
        return $result;
    }
}
