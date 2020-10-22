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

use PommProject\Foundation\ParameterHolder;
use PommProject\ModelManager\Generator\EntityGenerator;

/**
 * Console controller
 * Generate all Pomm base class
 */
class GenerateEntityController extends AbstractCliPommController implements ConsoleUsageProviderInterface
{
    private $filename;
    private $namespace;

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
            'generate-entity' => 'Generate an Entity class.',

            // Describe expected parameters
            array('<config-name>', 'Database configuration name to open a session.'),
            array('<schema>', 'Schema of the relation.'),
            array('<relation>', 'Relation with which we work'),
            array('--force', 'Force overwriting an existing file.'),
            array('--prefix-dir', 'Indicate a directory prefix.'),
            array('--prefix-ns', 'Indicate a namespace prefix.'),
            array('--flexible-container', 'Use an alternative flexible entity container.'),
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
     * Scan to generate all mapfiles
     */
    public function generateAction()
    {
        $this->checkConsole();

        // Get request and params
        $request = $this->getRequest();
        $options = $this->getToolOptions($request);
        $parameterList = array_merge($this->getParameters(), $options);

        $this->updateOutput(
            (new EntityGenerator(
                $this->getSession(),
                $this->getSchema(),
                $this->getRelation(),
                $this->getPathFile($this->getConfigName(), $this->getRelation()),
                $this->getNamespace($this->getConfigName()),
                $options['flexible-container']
            ))->generate(new ParameterHolder($parameterList))
        );

        return 'Entity generation for ' . $this->getConfigName() . '/' . $this->getSchema() . "\n";
    }
}
