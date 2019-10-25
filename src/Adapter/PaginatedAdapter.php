<?php

namespace Solcre\SolcreFramework2\Adapter;

use Solcre\SolcreFramework2\Entity\PaginatedResult;
use Zend\Paginator\Adapter\AdapterInterface;

class PaginatedAdapter implements AdapterInterface
{

    /**
     *
     * @var PaginatedResult
     */
    protected $paginatedResult;

    /**
     * Additional attributes to render with the collection
     *
     * @var array
     */
    private $additionalAttributes;

    public function __construct(PaginatedResult $paginatedResult)
    {
        $this->paginatedResult = $paginatedResult;
        $this->additionalAttributes = $paginatedResult->getAdditionalAttributes();
    }

    public function count($mode = 'COUNT_NORMAL')
    {
        return $this->paginatedResult->getTotalCount();
    }

    public function getItems($offset, $itemCountPerPage)
    {
        return $this->paginatedResult;
    }

    /**
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return $this->additionalAttributes;
    }
}
