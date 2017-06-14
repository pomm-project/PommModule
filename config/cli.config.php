<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

return array(
    'modules' => array(
        'Zend\Router',
        'Zend\ServiceManager\Di',
        'Zend\Session',
        'Zend\Log',
        'Zend\InputFilter',
        'Zend\Filter',
        'Zend\Validator',
        'Zend\Mvc\Console',
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
