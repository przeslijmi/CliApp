<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * CliApp stopped.
 */
class CliAppFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'CliApp stopped.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'appName' ];
}
