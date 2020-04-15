<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Operation called by CLI does not exists in CliApp.
 */
class OperationDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string $operationName Name of missing operation.
     */
    public function __construct(string $operationName)
    {

        // Lvd.
        $hint = 'Check CLI command. Isn\'t there any mismatch? Try to call app with `help` operation.';

        // Define.
        $this->addInfo('context', 'CliAppOperationDonoex');
        $this->addInfo('operationName', $operationName);
        $this->addHint($hint);
    }
}
