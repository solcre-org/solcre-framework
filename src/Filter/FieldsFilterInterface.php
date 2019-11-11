<?php

namespace Solcre\SolcreFramework2\Filter;

interface FieldsFilterInterface
{
    public function setFields($fields);
    public function filter($entity, $fields);
}
