<?php
/**
 * Base module for integration of Pomm projects with Laminas applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule\Service;

use RuntimeException;

use Interop\Container\ContainerInterface;

use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Return the Pomm options
 */
abstract class AbstractServiceFactory implements FactoryInterface
{
    /**
     * @var \Laminas\Stdlib\AbstractOptions
     */
    protected $options;

    /**
     * @param ContainerInterface $container
     * @param string $key
     * @param null|string $name
     * @return \Laminas\Stdlib\AbstractOptions
     * @throws \RuntimeException
     */
    public function getPommOptions(ContainerInterface $container)
    {
        // Get and check config
        $options = $container->get('Config');
        if (is_null($options) || !array_key_exists('pomm', $options)) {
            throw new \Exception('Options could not be found in "pomm".');
        }

        // Define default module's values
        foreach ($options['pomm']['databases'] as &$database) {
            if (!array_key_exists('class:session_builder', $database)) {
                $database['class:session_builder'] = '\PommProject\ModelManager\SessionBuilder';
            }
        }

        // Set options
        $options = $options['pomm'];
        $pommOptionsClass = $this->getOptionsClass();
        return new $pommOptionsClass($options);
    }

    /**
     * @abstract
     * @return string
     */
    abstract public function getOptionsClass();
}
