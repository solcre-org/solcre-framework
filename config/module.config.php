<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Solcre\SolcreFramework2\Filter;
use Solcre\SolcreFramework2\Filter\Factory as FilterFactory;
use Solcre\SolcreFramework2\Hydrator\EntityHydrator;
use Solcre\SolcreFramework2\Hydrator\Factory\EntityHydratorFactory;
use Solcre\SolcreFramework2\Service;
use Solcre\SolcreFramework2\Service\Factory;
use Zend\Hydrator\ArraySerializable;

return [
    'service_manager' => [
        'factories' => [
            Filter\FieldsFilterService::class => FilterFactory\FieldsFilterServiceFactory::class,
            Filter\ExpandFilterService::class => FilterFactory\ExpandFilterServiceFactory::class,
            Service\IdentityService::class    => Factory\IdentityServiceFactory::class
        ],
    ],
    'zf-hal'          => [
        'metadata_map' => [
            PersistentCollection::class => [
                'hydrator'     => ArraySerializable::class,
                'isCollection' => true,
            ],
            ArrayCollection::class      => [
                'isCollection' => true,
            ],
        ],
    ],
    'zf-rest'         => [
        'controllers' => [
            'collection_query_whitelist' => [
                'query',
                'fields',
            ]
        ]
    ],
    'hydrators'       => [
        'factories' => [
            EntityHydrator::class => EntityHydratorFactory::class
        ]
    ],
];
