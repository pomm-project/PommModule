<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommModule\Controller;

use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Request as ConsoleRequest;

use Pomm\Tools\OutputLine;
use Pomm\Tools\OutputLineStack;
use Pomm\Tools\ScanSchemaTool;
use Pomm\Tools\CreateBaseMapTool;

/**
 * Console controller
 * Generate defined Pomm base class
 */
class MapFileController extends AbstractCliPommController implements ConsoleUsageProviderInterface
{
    /**
     * Create a mapfile
     */
    public function generateAction()
    {
        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        $console = $this->getServiceLocator()->get('console');
        if (!$console instanceof Console) {
            throw new RuntimeException('Cannot obtain console adapter. Are we running in a console?');
        }

        $pommService = $this->getServiceLocator()->get('PommModule\Service\PommServiceFactory');
        $options = $this->getToolOptions($request);

        $tool = new CreateBaseMapTool($options);
        $tool->execute();
        $this->outputStack($tool->getOutputStack(), $console);

        return 'Generation done for ' . $options['database']->getName() . '/' . $options['schema'] . '/' . $options['schema'] . "\n";
    }

    /**
     * Explain the console usage
     * 
     * @param  Console $console The console used
     * @return array            The parameters of the command
     */
    public function getConsoleUsage(Console $console)
    {
        return array(
            // Describe available commands
            'mapfile-create <table> --database= --schema= --prefix-path= [--extends=] [--output-level=] [--prefix-namespace=]' 
            => 'Generates the Map file from a given table.',

            // Describe expected parameters
            array( '<table>',            'The table name to generate the map file from'),
            array( '--database',         'The name of the database to use (default: the first one)'),
            array( '--schema',           'The schema name to scan for tables'),
            array( '--prefix-path',      'The directory where the Model tree is located'),
            array( '--extends',          '(optional) The class the map file extends (default: "Pomm\Object\BaseObjectMap")'),
            array( '--output-level',     '(optional) The minimum log output level: DEBUG, INFO, WARNING, ERROR, CRITICAL (default: INFO)'),
            array( '--prefix-namespace', '(optional) The namespace prefix for the model namespace (default: none)'),
        );
    }

    /**
     * Complete the parent options tool
     * 
     * @param  ConsoleRequest $request The console
     * @return array                   An array of parameters
     */
    protected function getToolOptions(ConsoleRequest $request)
    {
        $options = parent::getToolOptions($request);
        $options['table'] = $request->getParam('table');
        return $options;
    }
}
