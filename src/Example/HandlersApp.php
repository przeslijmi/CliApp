<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Example;

use Przeslijmi\CliApp\CliApp;

/**
 * Handlers application.
 *
 * It differs from `SimpleApp` by having two handlers. One is started before cooking starts, second
 * is started after cooking ends.
 *
 * ## How to use this App.
 *
 * Create php file as described in README.md.
 *
 * Then start this app in commandline:
 *
 * ```
 * php file.php // to show help screen
 * php file.php cook // to start cook operation
 * ```
 */
class HandlersApp extends CliApp
{

    /**
     * Will change to true if was handled before.
     *
     * @var boolean
     */
    public $handledBefore = false;

    /**
     * Will change to true if was handled after.
     *
     * @var boolean
     */
    public $handledAfter = false;

    /**
     * Cook operation.
     *
     * @return void
     */
    public function cook() : void
    {

        echo 'cooking...';
    }

    /**
     * Handle something before.
     *
     * @return void
     */
    protected function handleBeforeOperation() : void
    {

        $this->handledBefore = true;
    }

    /**
     * Handle something after.
     *
     * @return void
     */
    protected function handleAfterOperation() : void
    {

        $this->handledAfter = true;
    }
}
