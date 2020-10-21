<?php
/**
 * Base module for integration of Pomm projects with Laminas applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule\Controller;

use Laminas\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Laminas\Console\Adapter\AdapterInterface as Console;
use Laminas\Console\Request as ConsoleRequest;

use PommProject\Foundation\ResultIterator;
use PommProject\Cli\Command\SessionAwareCommand;

/**
 * Console controller
 * Generate defined Pomm base class
 */
class InspectDatabaseController extends AbstractCliPommController implements ConsoleUsageProviderInterface
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
            'inspect-database <config-name>'
            => 'Get schemas in a database.',

            // Describe expected parameters
            array( '<config-name>', 'Database configuration name to open a session.'),
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
        $options['config-name'] = $request->getParam('config-name');
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
        $info = $this->getSession()->getInspector()->getSchemas();

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
        $output  = $this->colorize('Database inspection for ' . $this->getConfigName(), 'INFO') . "\n";
        $output .= sprintf("Found %d schemas in database.", $info->count()) . "\n";

        // Data table
        $table = new \Laminas\Text\Table\Table(array('columnWidths' => array(20, 10, 10, 60)));
        $table->appendRow(array('name', 'oid ', 'relations', 'comment'));
        foreach ($info as $schemaInfo) {
            $table->appendRow(array(
                $schemaInfo['name'],
                strval($schemaInfo['oid']),
                strval($schemaInfo['relations']),
                wordwrap($schemaInfo['comment'])
            ));
        }

        $output .= $table;

        return $output;
    }
}
