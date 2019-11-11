<?php

namespace Solcre\SolcreFramework2\Strategy;

use DoctrineModule\Stdlib\Hydrator\Strategy\AbstractCollectionStrategy;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

class ExpandEmbeddedStrategy extends AbstractCollectionStrategy
{
    protected $expand;

    public function getExpand()
    {
        return $this->expand;
    }

    public function setExpand($expand)
    {
        $this->expand = $expand;
    }

    public function extract($value)
    {
        //@@TODO: check value type
        $adapter = new ArrayAdapter($value->getCollection());
        $paginator = new Paginator($adapter);

        //Laod expand options
        $this->loadExpandOptions($paginator);

        //Iterate
        $forEmbedding = [];
        foreach ($paginator as $emb) {
            $forEmbedding[] = $emb;
        }

        //Create collection for hal
        $result = new ArrayCollection($forEmbedding);
        return $result;
    }

    protected function loadExpandOptions(Paginator &$paginator)
    {
        $expand = $this->expand;

        if (\is_array($expand) && count($expand) > 0) {
            foreach ($expand as $key => $value) {
                //Load expand
                $this->loadExpandOption($key, $value, $paginator);
            }
        }
    }

    protected function loadExpandOption($key, $value, Paginator &$paginator)
    {
        switch ($key) {
            case "size":
                $paginator->setItemCountPerPage((int) $value);
                break;
            case "page":
                $paginator->setCurrentPageNumber((int) $value);
                break;
        }
    }

    public function hydrate($value)
    {
    }
}
