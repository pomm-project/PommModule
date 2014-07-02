<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommModule\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Options for Pomm connection
 */
class Configuration extends AbstractOptions
{
    /**
     * @var array
     */
    protected $databases = array();

    /**
     * @param array $databases
     */
    public function setDatabases(Array $databases)
    {
        $this->databases = $databases;
    }

    /**
     * @return string
     */
    public function getDatabases()
    {
        return $this->databases;
    }
}