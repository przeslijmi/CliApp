<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * CliApp operation does not exists.
 */
class OperationDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'CliApp operation does not exists. Isn\'t there any mismatch?';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'appName', 'operationMethodName' ];
}
