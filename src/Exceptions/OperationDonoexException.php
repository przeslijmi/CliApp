<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Operation called by CLI does not exists in CliApp.
 */
class OperationDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $operationName, ?Exception $cause = null)
    {

        $this->setCodeName('OperationDonoex');
        $this->addInfo('context', 'CliAppOperationDonoex');
        $this->addInfo('operationName', $operationName);
        $this->addInfo('hint', 'Check CLI command. Isn\'t there any mismatch? Try to call app with `help` operation.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}