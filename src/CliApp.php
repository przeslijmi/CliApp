<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

use Exception;
use Przeslijmi\CliApp\Exceptions\OperationDonoexException;
use Przeslijmi\CliApp\Params;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\FileDonoexException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Silogger\Log;

/**
 * Parent for all CLI Applications taking care of parameters and communication.
 */
abstract class CliApp
{

    /**
     * Holds all params for this CLI application.
     *
     * @var string[]
     */
    private $params;

    /**
     * Log element.
     *
     * @var   Log
     * @since v1.0
     */
    private $log;

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        $this->params = new Params();
        $this->log('info', 'started ' . get_class($this));
    }

    /**
     * Getter for params object.
     *
     * @since  v1.0
     * @return Params
     */
    public function getParams() : Params
    {

        return $this->params;
    }

    /**
     * Start doing job. Runnes method of name equal to operation name.
     *
     * @since  v1.0
     * @return void
     */
    public function start() : void
    {

        // Perform job.
        try {

            // Include config if config (c) param was given.
            try {
                $this->includeConfig();
            } catch (Exception $exc) {
                throw new MethodFopException('cliAppIncludingConfigs', $exc);
            }

            // Call before operation handler (if exists).
            if (method_exists($this, 'handleBeforeOperation')) {
                $this->handleBeforeOperation();
            }

            // Lvd.
            $operation = $this->getParams()->getOperation();

            // Find operation to call.
            if (get_parent_class($this) === 'Przeslijmi\CliApp\CliApp') {
                $methodToCall = $operation;
            } else {
                $methodToCall = 'work';
            }

            // Log.
            $this->log('info', 'start to work on >>' . $methodToCall . '<<');

            // Check.
            if (method_exists($this, $methodToCall) === false) {
                throw new OperationDonoexException($methodToCall);
            }

            // Call.
            try {
                $this->$methodToCall();
            } catch (Sexception $exc) {
                throw new MethodFopException('callingCliAppOperation', $exc);
            }

            // Call after operation handler (if exists).
            if (method_exists($this, 'handleAfterOperation')) {
                $this->handleAfterOperation();
            }
        } catch (Exception $exc) {
            throw ( new ClassFopException('cliAppStopped', $exc) )
                ->addInfo('realClass', get_class($this));
        }
    }

    /**
     * Logs message.
     *
     * @param string $level   Name of level (see Silogger doc).
     * @param mixed  $message Message contents.
     *
     * @since  v1.0
     * @return void
     */
    protected function log(string $level, $message, array $context = []) : void
    {

        // Get log if not exists.
        if ($this->log === null) {
            $this->log = Log::get();
        }

        $this->log->log($level, get_class($this) . ' ' . $message, $context);
    }

    /**
     * Logs counter.
     *
     * @param string $level   Name of level (see Silogger doc).
     * @param mixed  $message Message contents.
     *
     * @since  v1.0
     * @return void
     */
    protected function logCounter(string $level, integer $current, integer $target, string $word = 'served') : void
    {

        // Get log if not exists.
        if ($this->log === null) {
            $this->log = Log::get();
        }

        $this->log->logCounter($level, $current, $target, $word);
    }

    /**
     * Includes configuration file if param `config` or `c` were given.
     *
     * @since  v1.0
     * @throws FileDonoexException If file does not exists.
     * @throws MethodFopException  If inclusion went wrong.
     * @return void
     */
    private function includeConfig() : void
    {

        // Short way - if not given - don't continue.
        if ($this->getParams()->isParamSet('c') === false) {
            return;
        }

        // Lvd.
        $configUri = (string) $this->getParams()->getParam('c');

        // File is not existing - that is not good.
        if (file_exists($configUri) === false) {

            // Prepare hint.
            $hint  = 'Config (c) param sent to application have to be an existing configuration file. ';
            $hint .= 'File is missing.';

            throw ( new FileDonoexException('cliAppConfigurationFile', $configUri) )
                ->addHint($hint);
        }

        // Try to include config file - throw otherwise.
        try {
            include $configUri;
        } catch (Exception $exc) {
            throw new MethodFopException('includingConfigFile', $exc);
        }

        // Log.
        $this->log('info', 'included configs ' . $configUri);
    }
}
