<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Example;

use Przeslijmi\CliApp\CliApp;

/**
 * Configs application.
 *
 * It differs from `SimpleApp` by having extra file with configuration that will be
 * included on bootstrap.
 *
 * ## How to use this App.
 *
 * Create php file as described in README.md.
 *
 * Add configuration file next to php file **configs.php**:
 * ```
 * ```
 *
 * Then start this app in commandline:
 *
 * ```
 * php file.php -c configs.php // to show help screen
 * php file.php cook -c configs.php // to start cook operation
 * ```
 */
class ConfigsApp extends CliApp
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
