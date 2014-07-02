<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommModule;

use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\Loader\StandardAutoloader;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Authentication\AuthenticationService;

use PommModule\Service\Authentication\PommAuthenticationAdapter;

/**
 * Integrate of Pomm projects with ZF2 applications
 */
class Module implements ConfigProviderInterface, InitProviderInterface, BootstrapListenerInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceManager;

    /**
     * {@inheritDoc}
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $event)
    {
        $this->serviceManager = $event->getTarget()->getServiceManager();
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\Storage\Session' => function($sm){
                    return new SessionStorage('proposalstudio7');
                },
                'Pomm\Authentication\AuthenticationService' => function($sm) {
                    $authAdapter = new PommAuthenticationAdapter();
                    $authAdapter->setServiceLocator($sm);
                    $authService = new AuthenticationService();
                    $authService->setAdapter($authAdapter);
                    $authService->setStorage($sm->get('Zend\Authentication\Storage\Session'));
                    return $authService;
                }
            ),
        );
    }
}
