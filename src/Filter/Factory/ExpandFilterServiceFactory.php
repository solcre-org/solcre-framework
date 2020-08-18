<?php

namespace Solcre\SolcreFramework2\Filter\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Solcre\SolcreFramework2\Filter\ExpandFilterService;

class ExpandFilterServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helpers = $container->get('ViewHelperManager');
        $halPlugin = $helpers->get('Hal');

        return new ExpandFilterService($halPlugin);
    }
}
