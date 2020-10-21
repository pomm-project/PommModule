<?php
/**
 * Base module for integration of Pomm projects with Laminas applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule;

use Laminas\ModuleManager\Feature\InitProviderInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\ModuleManagerInterface;
use Laminas\EventManager\EventInterface;
use Laminas\Loader\StandardAutoloader;

use PommProject\PommModule\Service\Authentication\PommAuthenticationAdapter;

/**
 * Integrate of Pomm projects with Laminas applications
 */
class Module implements ConfigProviderInterface, InitProviderInterface, BootstrapListenerInterface
{
    /**
     * @var \Laminas\ServiceManager\ServiceLocatorInterface
     */
    private $serviceManager;

    /**
     * {@inheritDoc}
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
    }

    public function onBootstrap(EventInterface $e)
    {
        $this->serviceManager = $e->getTarget()->getServiceManager();
        $sharedEvents = $e->getApplication()->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            $result->setTerminal(true);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
    }
}
