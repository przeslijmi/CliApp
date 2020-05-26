<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Calling CliApp operation failed.
 */
class CallingCliAppOperationFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Calling CliApp operation failed.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'appName', 'operationMethodName' ];
}
