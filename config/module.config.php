<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

return array(
    'service_manager' => array(
        'factories' =>  array(
            'PommProject\PommModule\Service\PommServiceFactory' => 'PommProject\PommModule\Service\PommServiceFactory',
        ),
    ),
    'controllers' => [
        'invokables' => [
            'PommProject\PommModule\Controller\GenerateDatabaseAllController'       => 'PommProject\PommModule\Controller\GenerateDatabaseAllController',
            'PommProject\PommModule\Controller\GenerateEntityController'            => 'PommProject\PommModule\Controller\GenerateEntityController',
            'PommProject\PommModule\Controller\GenerateRelationAllController'       => 'PommProject\PommModule\Controller\GenerateRelationAllController',
            'PommProject\PommModule\Controller\GenerateRelationModelController'     => 'PommProject\PommModule\Controller\GenerateRelationModelController',
            'PommProject\PommModule\Controller\GenerateRelationStructureController' => 'PommProject\PommModule\Controller\GenerateRelationStructureController',
            'PommProject\PommModule\Controller\GenerateSchemaAllController'         => 'PommProject\PommModule\Controller\GenerateSchemaAllController',
            'PommProject\PommModule\Controller\InspectConfigController'             => 'PommProject\PommModule\Controller\InspectConfigController',
            'PommProject\PommModule\Controller\InspectDatabaseController'           => 'PommProject\PommModule\Controller\InspectDatabaseController',
            'PommProject\PommModule\Controller\InspectRelationController'           => 'PommProject\PommModule\Controller\InspectRelationController',
            'PommProject\PommModule\Controller\InspectSchemaController'             => 'PommProject\PommModule\Controller\InspectSchemaController',
        ],
    ],
    'console' => array(
        'router' => array(
            'routes' => array(
                // Inspectors
                'inspect-config' => array(
                    'options' => array(
                        'route'    => 'inspect-config',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\InspectConfigController',
                            'action'     => 'inspect'
                        )
                    )
                ),
                'inspect-database' => array(
                    'options' => array(
                        'route'    => 'inspect-database <config-name>',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\InspectDatabaseController',
                            'action'     => 'inspect'
                        )
                    )
                ),
                'inspect-schema' => array(
                    'options' => array(
                        'route'    => 'inspect-schema <config-name> <schema>',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\InspectSchemaController',
                            'action'     => 'inspect'
                        )
                    )
                ),
                'inspect-relation' => array(
                    'options' => array(
                        'route'    => 'inspect-relation <config-name> <schema> <relation>',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\InspectRelationController',
                            'action'     => 'inspect'
                        )
                    )
                ),
                // Generators
                'generate-entity' => array(
                    'options' => array(
                        'route'    => 'generate-entity <config-name> <entity-name> <relation> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=]',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\GenerateEntityController',
                            'action'     => 'generate'
                        )
                    )
                ),
                'generate-model' => array(
                    'options' => array(
                        'route'    => 'generate-model <config-name> <schema> <relation> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=]',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\GenerateRelationModelController',
                            'action'     => 'generate'
                        )
                    )
                ),
                'generate-structure' => array(
                    'options' => array(
                        'route'    => 'generate-structure <config-name> <schema> <relation> [--prefix-dir=] [--prefix-ns=] [--flexible-container=]',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\GenerateRelationStructureController',
                            'action'     => 'generate'
                        )
                    )
                ),
                // Meta generators
                'generate-relation-all' => array(
                    'options' => array(
                        'route'    => 'generate-relation-all <config-name> <schema> <relation> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=] [--verbose]',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\GenerateRelationAllController',
                            'action'     => 'generate'
                        )
                    )
                ),
                'generate-schema-all' => array(
                    'options' => array(
                        'route'    => 'generate-schema-all <config-name> <schema> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=] [--verbose]',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\GenerateSchemaAllController',
                            'action'     => 'generate'
                        )
                    )
                ),
                'generate-database-all' => array(
                    'options' => array(
                        'route'    => 'generate-database-all <config-name> [--force] [--prefix-dir=] [--prefix-ns=] [--flexible-container=] [--verbose]',
                        'defaults' => array(
                            'controller' => 'PommProject\PommModule\Controller\GenerateDatabaseAllController',
                            'action'     => 'generate'
                        )
                    )
                ),
            )
        )
    ),
);
