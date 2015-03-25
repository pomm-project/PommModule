<?php
/**
 * Base module for integration of Pomm projects with ZF2 applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule\Controller;

use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;

use PommProject\Foundation\Pomm;
use PommProject\Foundation\Session\Session;
use PommProject\Foundation\Inspector\InspectorPooler;
use PommProject\Foundation\Inflector;

/**
 * Console controller
 * Generate Pomm base class
 */
abstract class AbstractCliPommController extends AbstractActionController implements ConsoleBannerProviderInterface
{
    private $pomm;
    private $session;
    private $configName;
    private $schema;
    private $relation;

    /**
     * Check if we're in console mode
     * 
     * @return void
     */
    protected function checkConsole()
    {
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        $console = $this->getServiceLocator()->get('console');
        if (!$console instanceof Console) {
            throw new RuntimeException('Cannot obtain console adapter. Are we running in a console?');
        }
    }

    /**
     * This method is defined in ConsoleBannerProviderInterface
     */
    public function getConsoleBanner(Console $console)
    {
        return 'Pomm2 Module 1.0.0';
    }

    /**
     * Get options from parameters
     * 
     * @param  ConsoleRequest $request The request
     * @return array                   An array of parameters
     */
    protected function getToolOptions(ConsoleRequest $request)
    {
        $pommService = $this->getServiceLocator()->get('PommProject\PommModule\Service\PommServiceFactory');
        $this->setPomm($pommService);

        $this->options = array();

        // Get parameters
        $this->configName = $request->getParam('config-name') ;
        $this->schema = $request->getParam('schema', 'public');
        $this->relation = $request->getParam('relation', '');

        // Get options
        $this->options['prefix-dir'] = $request->getParam('prefix-dir', 'module/Database/src/');
        $this->options['prefix-ns'] = $request->getParam('prefix-ns', 'Database');
        $this->options['force'] = $request->getParam('force', null);
        $this->options['flexible-container'] = $request->getParam('flexible-container', 'PommProject\ModelManager\Model\FlexibleEntity');

        return $this->options;
    }

    /**
     * Return all parameters
     * 
     * @return array The request parameters
     */
    protected function getParameters()
    {
        return array(
            'config-name' => $this->configName,
            'schema'      => $this->schema,
            'relation'    => $this->relation,
        );
    }

    /**
     * updateOutput
     *
     * Add ModelManager output lines to the CLI output.
     *
     * @access protected
     * @param  array            $lines
     * @return RelationAwareCommand
     */
    protected function updateOutput(array $lines = [])
    {
        $output = '';

        foreach ($lines as $line) {
            $status = $line["status"] == "ok" ? "✓" : "✗";

            switch ($line['operation']) {
                case "creating":
                    $operation = sprintf("%s", ucwords($line['operation']));
                    break;
                case "overwritting":
                    $operation = sprintf("%s", ucwords($line['operation']));
                    break;
                case "deleting":
                    $operation = sprintf("%s", ucwords($line['operation']));
                    break;
                default:
                    $operation = ucwords($line['operation']);
            }

            $output .= sprintf(
                " %s  %s file '%s'.",
                $status,
                $operation,
                $line['file']
            ) . "\n";
        }

        return $output;
    }

    /**
     * getFileName
     *
     * Create filename from parameters and namespace.
     *
     * @access protected
     * @param  string $configName
     * @param  string $fileSuffix
     * @return string
     */
    protected function getFileName($configName, $fileSuffix = '', $extraDir = '')
    {
        $elements =
            [
                ltrim($this->options['prefix-dir'], '/'),
                str_replace('\\', '/', trim($this->options['prefix-ns'], '\\')),
                Inflector::studlyCaps($configName),
                Inflector::studlyCaps(sprintf("%s_schema", $this->getSchema())),
                $extraDir,
                sprintf("%s%s.php", Inflector::studlyCaps($this->getRelation()), $fileSuffix)
            ];

        return join('/', array_filter(
            $elements,
            function ($val) {
                return $val != null;
            }
        ));
    }

    /**
     * getPathFile
     *
     * Create path file from parameters and namespace.
     *
     * @access protected
     * @param  string $configName
     * @param  string $fileSuffix
     * @param  string $extraDir
     * @param  string $fileName
     * @return string
     */
    protected function getPathFile($configName, $fileName, $fileSuffix = '', $extraDir = '')
    {
        $elements =
            [
                ltrim($this->options['prefix-dir'], '/'),
                str_replace('\\', '/', trim($this->options['prefix-ns'], '\\')),
                Inflector::studlyCaps($configName),
                Inflector::studlyCaps(sprintf("%s_schema", $this->getSchema())),
                $extraDir,
                sprintf("%s%s.php", Inflector::studlyCaps($fileName), $fileSuffix)
            ];

        return join('/', array_filter(
            $elements,
            function ($val) {
                return $val != null;
            }
        ));
    }

    /**
     * getNamespace
     *
     * Create namespace from parameters.
     *
     * @access protected
     * @param  string $configName
     * @param  string $extraNs
     * @return string
     */
    protected function getNamespace($configName, $extraNs = '')
    {
        $elements =
            [
                $this->options['prefix-ns'],
                Inflector::studlyCaps($configName),
                Inflector::studlyCaps(sprintf("%s_schema", $this->getSchema())),
                $extraNs
            ];

        return join('\\', array_filter(
            $elements,
            function ($val) {
                return $val != null;
            }
        ));
    }

    /**
     * fetchSchemaOid
     *
     * Get the schema Oid from database.
     *
     * @access protected
     * @return int $oid
     */
    protected function fetchSchemaOid()
    {
        $schemaOid = $this
            ->getSession()
            ->getInspector()
            ->getSchemaOid($this->getSchema())
            ;

        if ($schemaOid === null) {
            throw new PommModuleException(
                sprintf(
                    "Could not find schema '%s'.",
                    $this->getSchema()
                )
            );
        }

        return $schemaOid;
    }

    /**
     * setPomm
     *
     * When used with a framework, it is useful to get the Pomm instance from
     * the framwork configuration mechanism.
     *
     * @access public
     * @param  Pomm     $pomm
     * @return PommAwareCommand
     */
    public function setPomm(Pomm $pomm)
    {
        $this->pomm = $pomm;

        return $this;
    }

    /**
     * getPomm
     *
     * Return the Pomm instance.
     *
     * @access protected
     * @return Pomm
     */
    protected function getPomm()
    {
        return $this->pomm;
    }

    /**
     * setSession
     *
     * When testing, it is useful to provide directly the session to be used.
     *
     * @access public
     * @param  Session          $session
     * @return PommAwareController
     */
    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * getSession
     *
     * Return a session.
     *
     * @access protected
     * @return Session
     */
    protected function getSession()
    {
        if ($this->session === null) {
            $this->session = $this
                ->getPomm()
                ->getSession($this->configName)
                ->registerClientPooler(new InspectorPooler())
                ;
        }
        return $this->session;
    }

    /**
     * Set configuration name
     * 
     * @param  string $configName  The configuration name
     * @return PommAwareController
     */
    public function setConfigName($configName)
    {
        $this->configName = $configName;

        return $this;
    }

    /**
     * Get schema name
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * Set schema
     * 
     * @param  string $schema  The schema name
     * @return PommAwareController
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get schema
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Set relation
     * 
     * @param  string $relation  The relation name
     * @return PommAwareController
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * Get relation
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Decorate output
     * 
     * @param  string $text   The text
     * @param  string $status The decoration type
     * 
     * @return string         The decorated string
     */
    protected function colorize($text, $status)
    {
        $out = "";
        switch($status) {
            case "SUCCESS":
                $out = "[0;31m"; //Green background
                break;
            case "FAILURE":
                $out = "[0;31m"; //Red background
                break;
            case "WARNING":
                $out = "[1;33m"; //Yellow background
                break;
            case "INFO":
                $out = "[1;34m"; //Blue background
                break;
            default:
                throw new Exception("Invalid status: " . $status);
        }
        return chr(27) . "$out" . "$text" . chr(27) . "[0;37m";
    }
}
