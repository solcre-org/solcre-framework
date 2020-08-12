<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Laminas\Hydrator\ArraySerializable;
use Solcre\SolcreFramework2\AbstractFactory\BaseServiceAbstractFactory;
use Solcre\SolcreFramework2\Filter;
use Solcre\SolcreFramework2\Filter\Factory as FilterFactory;
use Solcre\SolcreFramework2\Hydrator\EntityHydrator;
use Solcre\SolcreFramework2\Hydrator\Factory\EntityHydratorFactory;

return [
    'service_manager' => [
        'factories'          => [
            Filter\FieldsFilterService::class => FilterFactory\FieldsFilterServiceFactory::class,
            Filter\ExpandFilterService::class => FilterFactory\ExpandFilterServiceFactory::class,
        ],
        'abstract_factories' => [
            BaseServiceAbstractFactory::class,
        ]
    ],
    'doctrine'        => [
        'configuration' => [
            'orm_default' => [
                'filters' => [
                    'search' => Filter\SearchFilter::class,
                ],
            ]
        ],
    ],
    'api-tools-hal'   => [
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
    'api-tools-rest'  => [
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
    'interfaces_classes' => []
];
