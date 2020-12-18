<?php

namespace Solcre\SolcreFramework2\Adapter;

use Laminas\Paginator\Adapter\AdapterInterface;
use Solcre\SolcreFramework2\Entity\PaginatedResult;

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

    public function count($mode = 'COUNT_NORMAL'): int
    {
        return $this->paginatedResult->getTotalCount();
    }

    public function getItems($offset, $itemCountPerPage)
    {
        return $this->paginatedResult->getItems();
    }

    /**
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return $this->additionalAttributes;
    }
}
