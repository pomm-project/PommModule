<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule\Service;

use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Return the Pomm options
 */
abstract class AbstractServiceFactory implements FactoryInterface
{
    /**
     * @var \Zend\Stdlib\AbstractOptions
     */
    protected $options;

    /**
     * @param ServiceLocatorInterface $sl
     * @param string $key
     * @param null|string $name
     * @return \Zend\Stdlib\AbstractOptions
     * @throws \RuntimeException
     */
    public function getPommOptions(ServiceLocatorInterface $sl)
    {
        // Get and check config
        $options = $sl->get('Config');
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
