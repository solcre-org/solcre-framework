<?php

namespace Solcre\SolcreFramework2\Filter\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\HelperPluginManager;
use Solcre\SolcreFramework2\Filter\FieldsFilterService;

class FieldsFilterServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helpers = $container->get('ViewHelperManager');
        if (($helpers instanceof HelperPluginManager) && $helpers->has('Hal')) {
            $halPlugin = $helpers->get('Hal');

            return new FieldsFilterService($halPlugin);
        }
        
        return null;
    }
}
