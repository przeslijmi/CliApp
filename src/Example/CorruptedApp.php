<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Example;

use Przeslijmi\CliApp\CliApp;

/**
 * Corrupted application.
 *
 * This is only for testing. Do not use it.
 *
 * @phpcs:disable Squiz.Commenting.ClassComment
 *
 * @codeCoverageIgnore
 */
class CorruptedApp extends CliApp
{

    /**
     * Cook operation.
     *
     * @return void
     */
    public function cook() : void
    {

        echhhhhho('cooking...');
    }
}
