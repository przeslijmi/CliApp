<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Param is of wrong type.
 */
class ParamWrotypeException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Param is of wrong type.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'paramName', 'typeExpected', 'actualType', 'operationMethodName' ];
}
