<?php

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Solcre\SolcreFramework2\Service\BaseService;
use Exception;
use ReflectionClass;
use Solcre\SolcreFramework2\Common\BaseRepository;
use Solcre\SolcreFramework2\Entity\PaginatedResult;
use Solcre\SolcreFramework2\Exception\BaseException;
use Solcre\SolcreFramework2\Filter\FilterInterface;
use Solcre\SolcreFramework2\Hydrator\EntityHydrator;
use Zend\Paginator\Paginator;

class BaseServiceTest extends TestCase
{
}
