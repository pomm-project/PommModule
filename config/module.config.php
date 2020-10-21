<?php
/**
 * Base module for integration of Pomm projects with Laminas applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

return [
    'service_manager' => [
        'factories' =>  [
            'pomm_service' =>  PommProject\PommModule\Service\PommServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            PommProject\PommModule\Controller\GenerateDatabaseAllController::class => PommProject\PommModule\Controller\GenerateDatabaseAllControllerFactory::class,
            PommProject\PommModule\Controller\GenerateEntityController::class => PommProject\PommModule\Controller\GenerateEntityControllerFactory::class,
            PommProject\PommModule\Controller\GenerateRelationAllController::class => PommProject\PommModule\Controller\GenerateRelationAllControllerFactory::class,
            PommProject\PommModule\Controller\GenerateRelationModelController::class => PommProject\PommModule\Controller\GenerateRelationModelControllerFactory::class,
            PommProject\PommModule\Controller\GenerateRelationStructureController::class => PommProject\PommModule\Controller\GenerateRelationStructureControllerFactory::class,
            PommProject\PommModule\Controller\GenerateSchemaAllController::class => PommProject\PommModule\Controller\GenerateSchemaAllControllerFactory::class,
            PommProject\PommModule\Controller\InspectConfigController::class => PommProject\PommModule\Controller\InspectConfigControllerFactory::class,
            PommProject\PommModule\Controller\InspectDatabaseController::class => PommProject\PommModule\Controller\InspectDatabaseControllerFactory::class,
            PommProject\PommModule\Controller\InspectRelationController::class => PommProject\PommModule\Controller\InspectRelationControllerFactory::class,
            PommProject\PommModule\Controller\InspectSchemaController::class => PommProject\PommModule\Controller\InspectSchemaControllerFactory::class,
        ],
    ],
    'console' => [
        'view_manager' => [
            'display_exceptions' => true,
        ],
        'router' => [
            'routes' => [
                // Inspectors
                'inspect-config' => [
                    'options' => [
                        'route'    => 'inspect-config',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\InspectConfigController::class,
                            'action'     => 'inspect'
                        ]
                    ]
                ],
                'inspect-database' => [
                    'options' => [
                        'route'    => 'inspect-database <config-name>',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\InspectDatabaseController::class,
                            'action'     => 'inspect'
                        ]
                    ]
                ],
                'inspect-schema' => [
                    'options' => [
                        'route'    => 'inspect-schema <config-name> <schema>',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\InspectSchemaController::class,
                            'action'     => 'inspect'
                        ]
                    ]
                ],
                'inspect-relation' => [
                    'options' => [
                        'route'    => 'inspect-relation <config-name> <schema> <relation>',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\InspectRelationController::class,
                            'action'     => 'inspect'
                        ]
                    ]
                ],
                // Generators
                'generate-entity' => [
                    'options' => [
                        'route'    => 'generate-entity <config-name> <schema> <relation> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=]',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\GenerateEntityController::class,
                            'action'     => 'generate'
                        ]
                    ]
                ],
                'generate-model' => [
                    'options' => [
                        'route'    => 'generate-model <config-name> <schema> <relation> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=]',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\GenerateRelationModelController::class,
                            'action'     => 'generate'
                        ]
                    ]
                ],
                'generate-structure' => [
                    'options' => [
                        'route'    => 'generate-structure <config-name> <schema> <relation> [--prefix-dir=] [--prefix-ns=] [--flexible-container=]',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\GenerateRelationStructureController::class,
                            'action'     => 'generate'
                        ]
                    ]
                ],
                // Meta generators
                'generate-relation-all' => [
                    'options' => [
                        'route'    => 'generate-relation-all <config-name> <schema> <relation> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=] [--verbose]',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\GenerateRelationAllController::class,
                            'action'     => 'generate'
                        ]
                    ]
                ],
                'generate-schema-all' => [
                    'options' => [
                        'route'    => 'generate-schema-all <config-name> <schema> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=] [--verbose]',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\GenerateSchemaAllController::class,
                            'action'     => 'generate'
                        ]
                    ]
                ],
                'generate-database-all' => [
                    'options' => [
                        'route'    => 'generate-database-all <config-name> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=] [--verbose]',
                        'defaults' => [
                            'controller' => PommProject\PommModule\Controller\GenerateDatabaseAllController::class,
                            'action'     => 'generate'
                        ]
                    ]
                ],
            ]
        ]
    ],
];
