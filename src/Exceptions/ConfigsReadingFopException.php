<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Reading configurations failed
 */
class ConfigsReadingFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Reading configurations failed.';
}
