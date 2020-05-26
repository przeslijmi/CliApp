<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * File with configurations to use is not existing.
 */
class ConfigFileDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'File with configurations to use is not existing.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'fileName' ];
}
