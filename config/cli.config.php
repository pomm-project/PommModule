<?php
/**
 * Base module for integration of Pomm projects with Laminas applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

return array(
    'modules' => array(
        'Laminas\Router',
        'Laminas\ServiceManager\Di',
        'Laminas\Session',
        'Laminas\Log',
        'Laminas\InputFilter',
        'Laminas\Filter',
        'Laminas\Validator',
        'Laminas\Mvc\Console',
        'PommProject\PommModule',
        'Database',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);
