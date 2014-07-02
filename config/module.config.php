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
);
