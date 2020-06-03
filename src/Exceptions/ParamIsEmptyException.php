<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Param value is empty while it should have contents.
 */
class ParamIsEmptyException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Param value is empty while it should have contents.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'paramName', 'operationMethodName' ];
}
