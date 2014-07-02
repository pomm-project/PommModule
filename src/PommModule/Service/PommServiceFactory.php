<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommModule\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

use Pomm\Connection\Database as PommDatabase;
use Pomm\Service as PommService;

/**
 * Initiate a connection to a database
 */
class PommServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Pomm\Connection\Database
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $service = new PommService();
        $databaseList = $this->getPommOptions($sl)->getDatabases();

        foreach ($databaseList as $databaseKey => $databaseOptionList) {
            $service->setDatabase(
                $databaseOptionList['name'], 
                new PommDatabase(
                    array(
                        'dsn' => $databaseOptionList['dsn']
                    )
                )
            );
        }
        return $service;
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return 'PommModule\Options\Configuration';
    }
}