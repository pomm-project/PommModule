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

use PommProject\ModelManager\Generator\EntityGenerator;
use PommProject\ModelManager\Generator\ModelGenerator;
use PommProject\ModelManager\Generator\StructureGenerator;
use PommProject\Foundation\ParameterHolder;

/**
 * Console controller
 * Generate all Pomm base class
 */
class GenerateRelationAllController extends AbstractCliPommController implements ConsoleUsageProviderInterface
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
            'generate-relation-all' => 'Generate structure, model and entity file for a given relation.',

            // Describe expected parameters
            array('<config-name>', 'Database configuration name to open a session.'),
            array('[<schema>]', 'Schema of the relation.'),
            array('--force', 'Force overwriting an existing file.'),
            array('--prefix-dir', 'Indicate a directory prefix.'),
            array('--prefix-ns', 'Indicate a namespace prefix.'),
            array('--flexible-container', 'Use an alternative flexible entity container.'),
            array('--verbose', 'Force overwriting an existing file.'),
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
            (new StructureGenerator(
                $this->getSession(),
                $this->getSchema(),
                $this->getRelation(),
                $this->getFileName($this->getConfigName(), null, 'AutoStructure'),
                $this->getNamespace($this->getConfigName(), 'AutoStructure')
            ))->generate(new ParameterHolder($parameterList))
        );

        $filename = $this->getFileName($this->getConfigName(), 'Model');
        if (!file_exists($filename) || $options['force']) {
            $this->updateOutput(
                (new ModelGenerator(
                    $this->getSession(),
                    $this->getSchema(),
                    $this->getRelation(),
                    $filename,
                    $this->getNamespace($this->getConfigName())
                ))->generate(new ParameterHolder($parameterList))
            );
        }

        $filename = $this->getFileName($this->getConfigName());
        if (!file_exists($filename) || $options['force']) {
            $this->updateOutput(
                (new EntityGenerator(
                    $this->getSession(),
                    $this->getSchema(),
                    $this->getRelation(),
                    $filename,
                    $this->getNamespace($this->getConfigName()),
                    $options['flexible-container']
                ))->generate(new ParameterHolder($parameterList))
            );
        }

        return 'Relation generation for ' . $this->getConfigName() . '/' . $this->getSchema() . '/' . $this->getRelation() . "\n";
    }

    /**
     * writelnSkipFile
     *
     * Write an informative message
     *
     * @access private
     * @param  string          $filename
     * @param  OutputInterface $output
     * @return void
     */
    private function writelnSkipFile($filename, $file_type = null)
    {
        $file_type = $file_type === null ? '' : sprintf("%s ", $file_type);

        return sprintf(
            " ✗  Preserving existing %sfile '%s'.",
            $file_type,
            $filename
        );
    }
}
