<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommModule\Service;

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
        $options = $sl->get('Config');
        $options = $options['pomm'];

        if (null === $options) {
            throw new RuntimeException('Options could not be found in "pomm".');
        }

        $pommOptionsClass = $this->getOptionsClass();

        return new $pommOptionsClass($options);
    }

    /**
     * @abstract
     * @return string
     */
    abstract public function getOptionsClass();
}