<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * File with configurations failed on include.
 */
class ConfigFileIncludeFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'File with configurations failed on include.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'fileName' ];
}
