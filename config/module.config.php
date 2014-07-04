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
            'PommModule\Service\PommServiceFactory' => 'PommModule\Service\PommServiceFactory',
        ),
        'invokables' => array(
            'pomm.authentication.default' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
    'controllers' => [
        'invokables' => [
            'mapfile' => 'PommModule\Controller\MapFileController',
            'scanmapfile' => 'PommModule\Controller\ScanMapFileController',
        ],
    ],
    'console' => array(
        'router' => array(
            'routes' => array(
                'pomm-mapfile-create' => array(
                    'options' => array(
                        'route'    => 'mapfile-create <table> --database= --schema= --prefix-path= [--extends=] [--output-level=] [--prefix-namespace=]',
                        'defaults' => array(
                            'controller' => 'mapfile',
                            'action'     => 'generate'
                        )
                    )
                ),
                'pomm-mapfile-scan' => array(
                    'options' => array(
                        'route'    => 'mapfile-scan --database= --schema= --prefix-path= [--extends=] [--output-level=] [--prefix-namespace=]',
                        'defaults' => array(
                            'controller' => 'scanmapfile',
                            'action'     => 'generate'
                        )
                    )
                )
            )
        )
    ),
);
