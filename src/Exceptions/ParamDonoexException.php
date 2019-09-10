<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Parameter needed by CliApp does not exists in CLI call.
 */
class ParamDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $paramName, ?Exception $cause = null)
    {

        $this->setCodeName('ParamDonoex');
        $this->addInfo('context', 'CliAppParamDonoex');
        $this->addInfo('paramName', $paramName);
        $this->addInfo('hint', 'Param `' . $paramName . '` is missing. When using this operation you have to define parameter `' . $paramName . '`. Try to call app with `help` operation for more info.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
