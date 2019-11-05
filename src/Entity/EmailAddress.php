<?php

namespace Solcre\SolcreFramework2\Entity;

class EmailAddress
{
    private $email;
    private $name;
    private $type;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function __construct($email = null, $name = null, $type = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->type = $type;
    }
}
