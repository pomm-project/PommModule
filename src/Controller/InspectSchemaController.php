<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule\Controller;

use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Request as ConsoleRequest;

use PommProject\PommModule\Exception\PommModuleException;

/**
 * Console controller
 * Generate all Pomm base class
 */
class InspectSchemaController extends AbstractCliPommController implements ConsoleUsageProviderInterface
{
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
            'inspect-database <config-name> <schema>'
            => 'Get relations informations in a schema.',

            // Describe expected parameters
            array( '<config-name>', 'Database configuration name to open a session.'),
            array( '<schema>', 'Name of the schema.'),
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
        return $options;
    }

    /**
     * Do the job!
     */
    public function inspectAction()
    {
        $this->checkConsole();

        // Get request and params
        $request = $this->getRequest();
        $options = $this->getToolOptions($request);

        // Get data
        $info = $this->getSession()->getInspector()->getSchemaRelations($this->fetchSchemaOid());

        return $this->formatOutput($info);
    }

    /**
     * Format output
     * 
     * @return string The output
     */
    protected function formatOutput($info)
    {
        // Summary
        $output  = $this->colorize('Schema inspection for ' . $this->getConfigName(), 'INFO') . "\n";
        $output .= sprintf("Found %d relations in schema '%s'.", $info->count(), $this->getSchema()) . "\n";

        // Data table
        $table = new \Zend\Text\Table\Table(array('columnWidths' => array(20, 10, 10, 60)));
        $table->appendRow(array('name', 'type', 'oid ', 'comment'));
        foreach ($info as $tableInfo) {
            $table->appendRow(array(
                $tableInfo['name'],
                $tableInfo['type'],
                strval($tableInfo['oid']),
                wordwrap($tableInfo['comment'])
            ));
        }

        $output .= $table;
        
        return $output;
    }
}
