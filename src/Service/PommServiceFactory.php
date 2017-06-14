<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

use Interop\Container\ContainerInterface;

use PommProject\Foundation\Pomm as PommService;

/**
 * Initiate a connection to a database
 */
class PommServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Pomm\Connection\Database
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $databaseList = $this->getPommOptions($container)->getDatabases();
        $service = new PommService($databaseList);

        return $service;
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return 'PommProject\PommModule\Options\Configuration';
    }
}
