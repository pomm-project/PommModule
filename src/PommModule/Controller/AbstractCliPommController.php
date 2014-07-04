<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommModule\Controller;

use Pomm\Tools\OutputLine;
use Pomm\Tools\OutputLineStack;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Console controller
 * Generate Pomm base class
 */
Abstract class AbstractCliPommController extends AbstractActionController
{
    /**
     * Get options from parameters
     * 
     * @param  ConsoleRequest $request The request
     * @return array                   An array of parameters
     */
    protected function getToolOptions(ConsoleRequest $request)
    {
        $options = array();
        $pommService = $this->getServiceLocator()->get('PommModule\Service\PommServiceFactory');

        $options['database'] = $request->getParam('database', '') == '' ? $pommService->getDatabase() : $pommService->getDatabase($request->getParam('database'));
        $options['prefix_dir'] = $request->getParam('prefix-path', getcwd());
        if (!is_null($request->getParam('prefix-namespace', null))) {
            $options['prefix_namespace'] = $request->getParam('prefix-namespace');
        }
        $options['schema'] = $request->getParam('schema', 'public');
        $options['extends'] = $request->getParam('extends', 'BaseObjectMap');

        $outputLevel = $request->getParam('output-level');
        if (in_array(
            strtoupper($outputLevel),
            array('', 'DEBUG', 'INFO', 'WARNING', 'ERROR', 'CRITICAL')
        )) {
            $options['output_level'] = $outputLevel != ''
                ? constant('\Pomm\Tools\OutputLine::LEVEL_'.strtoupper($outputLevel))
                : OutputLine::LEVEL_INFO;
        }
        else {
            throw new \Exception(
                "Invalid log output level: {$request->getParam('output-level')}"
                ."\nAvailable levels: DEBUG, INFO (default), WARNING, ERROR, CRITICAL"
            );
        }

        return $options;
    }

    /**
     * Output the Pomm generation log
     * 
     * @param  OutputLineStack $stack   The log
     * @param  Console         $console The console
     * @return void
     */
    protected function outputStack(OutputLineStack $stack, Console $console)
    {
        foreach ($stack as $outputLine) {
            $console->writeLine((string) $outputLine);
        }
    }
}
