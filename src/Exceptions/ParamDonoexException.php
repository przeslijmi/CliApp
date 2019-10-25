<?php declare(strict_types=1);

namespace Przeslijmi\CliApp\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Parameter needed by CliApp does not exists in CLI call.
 */
class ParamDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string $paramName Name of missing param.
     *
     * @since v1.0
     */
    public function __construct(string $paramName)
    {

        // Lvd.
        $hint  = 'Param `' . $paramName . '` is missing. When using this operation ';
        $hint .= 'you have to define parameter `' . $paramName . '`. Try to call ';
        $hint .= 'app with `help` operation for more info.';

        // Define.
        $this->addInfo('context', 'CliAppParamDonoex');
        $this->addInfo('paramName', $paramName);
        $this->addInfo('hint', $hint);
    }
}
