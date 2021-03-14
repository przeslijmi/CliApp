<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

use stdClass;
use Przeslijmi\Silogger\Log;

class Process
{

    /**
     * Holds unique process id.
     *
     * @var string
     */
    private $uniqueProcessId;

    /**
     * Constructor.
     */
    public function __construct(string $uniqueProcessId)
    {

        $this->uniqueProcessId = $uniqueProcessId;
    }

    public function get() : stdClass
    {

        // Empty answer.
        if (file_exists($this->getUri()) === false) {
            return new stdClass();
        }

        return json_decode(file_get_contents($this->getUri()));
    }

    /**
     * Define process started.
     *
     * @return void
     */
    public function start() : void
    {

        // Reuse file file - if exists.
        $contents = $this->get();

        // Define contents.
        if (isset($contents->upid) === false) {
            $contents->upid = $this->uniqueProcessId;
        }
        if (isset($contents->start) === false) {
            $contents->start = date('Y-m-d H:i:s');
        }

        // Overwrite stop.
        $contents->stop = null;

        // Save file.
        file_put_contents($this->getUri(), json_encode($contents, JSON_PRETTY_PRINT));
    }

    public function finish() : void
    {

        // Log.
        Log::get()->localeLog('notice', 'Przeslijmi\CliApp', 'ProcessFinished');

        // Get data.
        $contents = $this->get();

        // Add stop date if start date is present.
        if (isset($contents->start) === true) {
            $contents->stop = date('Y-m-d H:i:s');
        }

        // Save file.
        file_put_contents($this->getUri(), json_encode($contents, JSON_PRETTY_PRINT));
    }

    private function getUri() : string
    {

        // Calc dir.
        $dir  = dirname(dirname(__FILE__));
        $dir  = str_replace('\\', '/', $dir);
        $dir  = rtrim($dir, '/') . '/resources/.upid/';

        // Create dir if not exists.
        if (file_exists($dir) === false) {
            mkdir($dir);
        }

        return $dir . $this->uniqueProcessId;
    }
}
