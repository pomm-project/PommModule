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
use Laminas\Text\Table\Table;

use PommProject\Foundation\ResultIterator;
use PommProject\Cli\Command\SessionAwareCommand;
use PommProject\PommModule\Exception\PommModuleException;

/**
 * Console controller
 * Generate defined Pomm base class
 */
class InspectRelationController extends AbstractCliPommController implements ConsoleUsageProviderInterface
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
            'inspect-relation <config-name> <schema> <relation>'
            => 'Get information about a relation.',

            // Describe expected parameters
            array( '<config-name>', 'Database configuration name to open a session.'),
            array( '<schema>', 'Name of the schema.'),
            array( '<relation>', 'Name of the relation.'),
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
        $this->relationOid = $this->getSession()
            ->getInspector()
            ->getTableOid($this->getSchema(), $this->getRelation());
        if ($this->relationOid === null) {
            throw new PommModuleException(
                sprintf(
                    "Relation %s.%s not found.",
                    $this->getSchema(),
                    $this->getRelation()
                )
            );
        }
        $fieldInfoList = $this->getSession()
            ->getInspector()
            ->getTableFieldInformation($this->relationOid);

        return $this->formatOutput($fieldInfoList);
    }

    /**
     * Format output
     *
     * @return string The output
     */
    protected function formatOutput($fieldInfoList)
    {
        // Summary
        $output  = $this->colorize('Relation inspection for ' . $this->getConfigName(), 'INFO') . "\n";
        $output .= sprintf("Relation %s.%s", $this->getSchema(), $this->getRelation()) . "\n";

        // Data table
        $table = new Table(array('columnWidths' => array(5, 20, 20, 20, 15, 40)));
        $table->appendRow(array('pk', 'name', 'type', 'default', 'notnull', 'comment'));
        foreach ($fieldInfoList as $fieldInfo) {
            $table->appendRow(array(
                $fieldInfo['is_primary'] ? '*' : '',
                $fieldInfo['name'],
                $this->formatType($fieldInfo['type']),
                $fieldInfo['default'],
                $fieldInfo['is_notnull'] ? 'yes' : 'no',
                wordwrap($fieldInfo['comment']),
            ));
        }

        $output .= $table;

        return $output;
    }

    /**
     * formatType
     *
     * Format type.
     *
     * @access protected
     * @param string $type
     * @return string
     */
    protected function formatType($type)
    {
        if (preg_match('/^(?:(.*)\.)?_(.*)$/', $type, $matchs)) {
            if ($matchs[1] !== '') {
                return sprintf("%s.%s[]", $matchs[1], $matchs[2]);
            } else {
                return $matchs[2].'[]';
            }
        } else {
            return $type;
        }
    }
}
