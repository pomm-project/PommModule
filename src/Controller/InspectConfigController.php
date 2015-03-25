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

use PommProject\Foundation\ResultIterator;
use PommProject\Cli\Command\SessionAwareCommand;

/**
 * Console controller
 * Generate defined Pomm base class
 */
class InspectConfigController extends AbstractCliPommController implements ConsoleUsageProviderInterface
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
            'inspect-config' => 'Get configuration of a database.',
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
        $results = array_keys($this->getPomm()->getSessionBuilders());

        return $this->formatOutput($results);
    }

    private function showResultList(array $resultList)
    {
        // Data table
        $table = new \Zend\Text\Table\Table(array('columnWidths' => array(20)));
        $table->appendRow(array('name'));
        foreach ($resultList as $result) {
            $table->appendRow(array(
                $result
            ));
        }
        return $table;
    }

    /**
     * Format output
     * 
     * @return string The output
     */
    protected function formatOutput($results)
    {
        // Summary
        $output = $this->colorize('Config inspection for ' . $this->getConfigName(), 'INFO') . "\n";
        switch(count($results)) {
            case 0:
                $output .= "There are no session builders in current Pomm instance.";
                break;
            case 1:
                $output .= "There is 1 builder in current Pomm instance:\n" . $this->showResultList($results);
                break;
            default:
                $output .= sprintf("There are %d builders in current Pomm instance:", count($results)) . "\n" . $this->showResultList($results);
        }
        
        return $output;
    }
}
