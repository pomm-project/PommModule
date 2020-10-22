<?php
/**
 * Base module for integration of Pomm projects with Laminas applications
 *
 * @license MIT
 * @link    http://www.pomm-project.org/
 * @author  Martin Supiot <msupiot@jack.fr>
 */

namespace PommProject\PommModule\Controller;

use Laminas\Console\Adapter\AdapterInterface as Console;
use Laminas\Mvc\Console\Controller\AbstractConsoleController as LaminasAbstractConsoleController;

/**
 * Admin Console controller
 */
abstract class AbstractConsoleController extends LaminasAbstractConsoleController
{
    /** @var int Timer */
    protected $timer;

    /** @var string current user organization_id **/
    protected $organizationId;

    /** @var string current user user_id **/
    protected $userId;

    /**
     * Check if we're in console mode
     * @return void
     */
    protected function checkConsole()
    {
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$this->console instanceof Console) {
            throw new \RuntimeException('Cannot obtain console adapter. Are we running in a console?');
        }
    }

    /**
     * Prepare an informational console message
     * @codeCoverageIgnore
     * @param  string  $text    The text to output
     * @param  boolean $verbose The verbose mode, on by default
     * @return string           The console output
     */
    protected function responseInfo($text, $verbose = true)
    {
        if (!$verbose) {
            $text = '.';
        }
        return $this->colorize($text, "INFO");
    }

    /**
     * Prepare a success console message
     * @codeCoverageIgnore
     * @param  string  $text    The text to output
     * @param  boolean $verbose The verbose mode, on by default
     * @return string           The console output
     */
    protected function responseSuccess($text, $verbose = true)
    {
        if (!$verbose) {
            $text = '.';
        }
        return $this->colorize($text, "SUCCESS");
    }

    /**
     * Prepare a failed console message
     * @codeCoverageIgnore
     * @param  string  $text    The text to output
     * @param  boolean $verbose The verbose mode, on by default
     * @return string           The console output
     */
    protected function responseFailure($text, $verbose = true)
    {
        if (!$verbose) {
            $text = '.';
        }
        return $this->colorize($text, "FAILURE");
    }

    /**
     * Start the timer
     * @codeCoverageIgnore
     * @return void
     */
    protected function startTimer()
    {
        $this->time = time();
    }

    /**
     * End the timer
     * @codeCoverageIgnore
     * @return int The timer result in seconds
     */
    protected function endTimer()
    {
        $this->time = time() - $this->time;
        return $this->time;
    }

    /**
     * Decorate output
     * @codeCoverageIgnore
     * @param  string $text   The text
     * @param  string $status The decoration type
     * @return string         The decorated string
     */
    protected function colorize($text, $status)
    {
        $out = "";
        switch ($status) {
            case "SUCCESS":
                $out = "[0;32m"; //Green
                break;
            case "FAILURE":
                $out = "[0;31m"; //Red
                break;
            case "WARNING":
                $out = "[1;33m"; //Yellow
                break;
            case "INFO":
                $out = "[1;34m"; //Blue
                break;
            default:
                throw new \Exception("Invalid status: " . $status);
        }
        return chr(27) . "$out" . "$text" . chr(27) . "[0;37m" . "\n";
    }
}
