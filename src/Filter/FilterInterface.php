<?php

namespace Solcre\SolcreFramework2\Filter;

interface FilterInterface
{
    public function getName();

    public function setOptions($options);

    public function prepareOptions($options);

    public function filter($entity, $options = null);

    public function removeFilter($entity);

    public function canFilter($options);
}
