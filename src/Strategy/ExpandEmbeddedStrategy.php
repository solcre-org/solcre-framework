<?php

namespace Solcre\SolcreFramework2\Strategy;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Laminas\Hydrator\Strategy\AbstractCollectionStrategy;
use Laminas\Paginator\Adapter\ArrayAdapter;
use Laminas\Paginator\Paginator;
use function count;
use function is_array;

class ExpandEmbeddedStrategy extends AbstractCollectionStrategy
{
    protected $expand;

    public function getExpand()
    {
        return $this->expand;
    }

    public function setExpand($expand): void
    {
        $this->expand = $expand;
    }

    public function extract($value, ?object $object = null)
    {
        //@@TODO: check value type
        $adapter   = new ArrayAdapter($value->getCollection());
        $paginator = new Paginator($adapter);

        //Load expand options
        $this->loadExpandOptions($paginator);

        //Iterate
        $forEmbedding = [];
        foreach ($paginator as $emb) {
            $forEmbedding[] = $emb;
        }

        //Create collection for hal
        return new ArrayCollection($forEmbedding);
    }

    protected function loadExpandOptions(Paginator $paginator): void
    {
        $expand = $this->expand;

        if (is_array($expand) && count($expand) > 0) {
            foreach ($expand as $key => $value) {
                //Load expand
                $this->loadExpandOption($key, $value, $paginator);
            }
        }
    }

    protected function loadExpandOption($key, $value, Paginator $paginator): void
    {
        switch ($key) {
            case 'size':
                $paginator->setItemCountPerPage((int)$value);
                break;
            case 'page':
                $paginator->setCurrentPageNumber((int)$value);
                break;
        }
    }

    public function hydrate($value, ?array $data)
    {
    }
}
