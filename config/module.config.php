<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Solcre\SolcreFramework2\Common\SearchFilter;
use Solcre\SolcreFramework2\Filter\ExpandFilterService;
use Solcre\SolcreFramework2\Filter\Factory\ExpandFilterServiceFactory;
use Solcre\SolcreFramework2\Filter\Factory\FieldsFilterServiceFactory;
use Solcre\SolcreFramework2\Filter\FieldsFilterService;
use Solcre\SolcreFramework2\Hydrator\EntityHydrator;
use Solcre\SolcreFramework2\Hydrator\Factory\EntityHydratorFactory;
use Solcre\SolcreFramework2\Service\EmailService;
use Solcre\SolcreFramework2\Service\ExcelService;
use Solcre\SolcreFramework2\Service\Factory\EmailServiceFactory;
use Solcre\SolcreFramework2\Service\Factory\ExcelServiceFactory;
use Solcre\SolcreFramework2\Service\Factory\ScheduleEmailServiceFactory;
use Solcre\SolcreFramework2\Service\Factory\SendScheduleEmailServiceFactory;
use Solcre\SolcreFramework2\Service\ScheduleEmailService;
use Solcre\SolcreFramework2\Service\SendScheduleEmailService;
use Zend\Hydrator\ArraySerializable;

return [
    'service_manager' => [
        'factories' => [
            EmailService::class             => EmailServiceFactory::class,
            FieldsFilterService::class      => FieldsFilterServiceFactory::class,
            ExpandFilterService::class      => ExpandFilterServiceFactory::class,
            ScheduleEmailService::class     => ScheduleEmailServiceFactory::class,
            SendScheduleEmailService::class => SendScheduleEmailServiceFactory::class

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
