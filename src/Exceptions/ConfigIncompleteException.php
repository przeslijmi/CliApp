<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * At least one of configuration is missing or in wrong type.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class ConfigIncompleteException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Check causes! At least one of configuration is missing or in wrong type to perform command line operation.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'operationMethodName' ];
}
