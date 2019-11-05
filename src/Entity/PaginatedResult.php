<?php

namespace Solcre\SolcreFramework2\Entity;

use \ArrayIterator;
use \IteratorAggregate;
use Traversable;

class PaginatedResult implements IteratorAggregate
{
    protected $totalCount;
    protected $items;

    /**
     * Additional attributes to render with the collection
     *
     * @var array
     */
    private $additionalAttributes;

    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getIterator()
    {
        $items = $this->items;

        if (! $items instanceof Traversable) {
            $items = new ArrayIterator($items);
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return $this->additionalAttributes;
    }

    public function __construct($items, $totalCount = -1, array $additionalAttributes = [])
    {
        $this->totalCount = $totalCount;
        $this->items = $items;
        $this->additionalAttributes = $additionalAttributes;
    }
}
