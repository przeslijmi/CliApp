<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

use Przeslijmi\CliApp\Exceptions\CallingCliAppOperationFopException;
use Przeslijmi\CliApp\Exceptions\CliAppFopException;
use Przeslijmi\CliApp\Exceptions\OperationDonoexException;
use Przeslijmi\CliApp\Exceptions\ConfigFileDonoexException;
use Przeslijmi\CliApp\Params;
use Przeslijmi\Sexceptions\Exceptions\ConfigFileIncludeFopException;
use Przeslijmi\Silogger\Log;
use Throwable;

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
     * @var Log
     */
    private $log;

    /**
     * Log name to use.
     *
     * @var string
     */
    private $logName = 'default';

    /**
     * Constructor.
     */
    public function __construct()
    {

        $this->params = new Params();
        $this->log('info', 'started ' . get_class($this));
    }

    /**
     * Getter for params object.
     *
     * @return Params
     */
    public function getParams() : Params
    {

        return $this->params;
    }

    /**
     * Start doing job. Runnes method of name equal to operation name.
     *
     * @throws ConfigsReadingFopException When including configs failed or calling method failed.
     * @throws OperationDonoexException When operation called does not exists.
     * @throws CallingCliAppOperationFopException When calling operation failed.
     * @throws CliAppFopException Parent - when this method somehow failed.
     * @return void
     */
    public function start() : void
    {

        // Perform job.
        try {

            // Include config if config (c) param was given.
            try {
                $this->includeConfig();
            } catch (Throwable $thr) {
                throw new ConfigsReadingFopException([], 0, $thr);
            }

            // Call before operation handler (if exists).
            if (method_exists($this, 'handleBeforeOperation') === true) {
                $this->handleBeforeOperation();
            }

            // Lvd.
            $operation = $this->getParams()->getOperation();

            // Log.
            $this->log('info', 'start to work on >>' . $operation . '<<');

            // Check.
            if (method_exists($this, $operation) === false) {
                throw new OperationDonoexException([ get_class($this), $operation ]);
            }

            // Call.
            try {
                $this->$operation();
            } catch (Throwable $thr) {
                throw new CallingCliAppOperationFopException([ get_class($this), $operation ], 0, $thr);
            }

            // Call after operation handler (if exists).
            if (method_exists($this, 'handleAfterOperation') === true) {
                $this->handleAfterOperation();
            }
        } catch (Throwable $thr) {
            throw new CliAppFopException([ get_class($this) ], 0, $thr);
        }//end try
    }

    /**
     * Setter for log name.
     *
     * @param string $logName Log name.
     *
     * @return self
     */
    public function setLogName(string $logName) : self
    {

        // Save.
        $this->logName = $logName;

        return $this;
    }

    /**
     * Deletes log.
     *
     * @return self
     */
    public function deleteLog() : self
    {

        // Delete log.
        $this->log = null;

        return $this;
    }

    /**
     * Logs message.
     *
     * @param string $level   Name of level (see Silogger doc).
     * @param mixed  $message Message contents.
     * @param array  $context Extra information to save to log.
     *
     * @return void
     */
    public function log(string $level, $message, array $context = []) : void
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
     * @param string  $level   Name of level (see Silogger doc).
     * @param integer $current Current value of counter.
     * @param integer $target  Final value of counter.
     * @param string  $word    Optional, 'served'. What prefix use before counter.
     *
     * @return void
     */
    public function logCounter(string $level, int $current, int $target, string $word = 'served') : void
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
     * @throws ConfigFileDonoexException Wher file does not exists.
     * @throws ConfigFileIncludeFopException When file exists but failed to include it.
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

            throw new ConfigFileDonoexException([ $configUri ]);
        }

        // Try to include config file - throw otherwise.
        try {
            include $configUri;
        } catch (Throwable $thr) {
            throw new ConfigFileIncludeFopException([ $configUri ], 0, $thr);
        }

        // Log.
        $this->log('info', 'included configs ' . $configUri);
    }
}
