<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Example;

use Przeslijmi\CliApp\CliApp;

/**
 * Simple application.
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
class SimpleApp extends CliApp
{

    /**
     * Cook operation.
     *
     * @return void
     */
    public function cook() : void
    {

        echo 'cooking...';
    }
}
