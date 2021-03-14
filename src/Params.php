<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

use Przeslijmi\CliApp\CliApp;
use Przeslijmi\CliApp\Exceptions\ConfigIncompleteException;
use Przeslijmi\CliApp\Exceptions\ParamDonoexException;
use Przeslijmi\CliApp\Exceptions\ParamIsEmptyException;
use Przeslijmi\CliApp\Exceptions\ParamWrotypeException;
use stdClass;
use Throwable;

/**
 * Param mechanism for CLI Applications.
 */
class Params
{

    /**
     * Holds all params;
     *
     * @var array
     */
    private $params = [];

    /**
     * Operation name.
     *
     * @var string
     */
    private $operation = 'help';

    /**
     * Holds list of aliases. Key is an alias, value is an original field.
     *
     * @var array
     */
    private $aliases = [];

    /**
     * Parent APP that created this Params object.
     *
     * @var CliApp
     */
    private $parentApp;

    /**
     * Constructor - defines default params 'upid' and 'config' (with 'c' alias)
     *
     * @param CliApp $parentApp Parent APP that created this Params object.
     */
    public function __construct(CliApp $parentApp)
    {

        $this->parentApp = $parentApp;
        $this->setParam('config', '');
        $this->setAliases('config', 'c');
        $this->setParam('upid', '');
    }

    /**
     * Getter for params.
     *
     * @return array
     */
    public function get() : array
    {

        return $this->params;
    }

    /**
     * Getter for one param.
     *
     * @param string  $name  Name of the param to give value.
     * @param boolean $throw Optional, true. Throw if param does not exists.
     *
     * @throws ParamDonoexException When no param with given name has been found.
     * @return mixed
     */
    public function getParam(string $name, bool $throw = true)
    {

        if (isset($this->params[$name]) === false && $throw === true) {
            throw new ParamDonoexException([ $name ]);
        } elseif (isset($this->params[$name]) === false && $throw === false) {
            return null;
        }

        return $this->params[$name];
    }

    /**
     * Retun list off all params.
     *
     * @return array
     */
    public function getParams() : array
    {

        return $this->params;
    }

    /**
     * Returns name of operation to perform by CliApp.
     *
     * @return string
     */
    public function getOperation() : string
    {

        // Conclude.
        if ($this->operation === '?'
            || $this->operation === 'h'
            || empty($this->operation) === true
        ) {
            $this->operation = 'help';
        }

        return $this->operation;
    }

    /**
     * Check if this param has been used (even if without value).
     *
     * @param string $name Name of the param to check.
     *
     * @return boolean
     */
    public function isParamSet(string $name) : bool
    {

        return isset($this->params[$name]);
    }

    /**
     * Changes `argv` std php array into key/value array.
     *
     * @param array   $params           Contents of `argv` array.
     * @param boolean $setOperationAlso Optional, true. If set to false operation will not be set.
     *
     * @return self
     */
    public function set(array $params, bool $setOperationAlso = true) : self
    {

        // Shortcut - do nothing.
        if (count($params) < 2) {
            return $this;
        }

        // Save operation if it is given.
        if (substr($params[1], 0, 1) !== '-' && $setOperationAlso === true) {
            $this->setOperation($params[1]);
        }

        // Lvd.
        $name = '';

        // Scan every element of `argv` and if you find anything starting with
        // at least one dash assume this is a name of the param. In next iteration
        // if at least one dash is in start - assume new param, but otherwise
        // assume it is a value of the previous param.
        for ($i = 1; $i < count($params); ++$i) {

            // Lvd.
            $param = $params[$i];

            // Main logic.
            if (substr($param, 0, 1) === '-') {
                $name  = ltrim($param, '-');
                $value = '';
            } else {
                $value = $param;
            }

            // Finally set.
            $this->setParam($name, $value);
        }

        return $this;
    }

    /**
     * Setter for one param.
     *
     * @param string                       $name  Name of param.
     * @param string|integer|float|boolean $value Value of param.
     *
     * @return self
     */
    public function setParam(string $name, $value) : self
    {

        // Set this param.
        $this->params[$name] = $value;

        // If this was an alias - change original name also.
        if (isset($this->aliases[$name]) === true) {
            $originalName                = $this->aliases[$name];
            $this->params[$originalName] = $this->params[$name];
        }

        // If this is upid (unique process id) param - inform CliApp about it.
        if ($name === 'upid' && empty($value) === false) {
            $_SERVER['PRZESLIJMI_CLIAPP_UPID'] = $value;
            $this->parentApp->addProcess($value);
        }

        return $this;
    }

    /**
     * Setter for operation.
     *
     * @param string $operation Name of operation.
     *
     * @return self
     */
    public function setOperation(string $operation) : self
    {

        $this->operation = $operation;

        return $this;
    }

    /**
     * Sets aliases to include on setting values. Has to be called before `->set()`.
     *
     * @param string   $param      Param in long version.
     * @param string[] ...$aliases Aliases of the long version name.
     *
     * @return self
     *
     * phpcs:disable Squiz.Commenting.FunctionComment.IncorrectTypeHint
     * phpcs:disable MySource.Commenting.FunctionComment.IncorrectTypeHint
     */
    public function setAliases(string $param, string ...$aliases) : self
    {

        // Remember.
        foreach ($aliases as $alias) {
            $this->aliases[$alias] = $param;
        }

        return $this;
    }

    /**
     * Called to check if params are properly set.
     *
     * ## Usage example
     * ```
     * $his->validateParams([
     *     [ 'param0', 'string', false ],      // has to be string but can be empty
     *     [ 'param1', 'string|array', true ], // has to be array or string and non-empty
     *     [ 'param1', 'stdCalss', true ],     // has to be object of stdClass and non-empty
     * ]);
     * ```
     *
     * @param array $definitions Definitions of params to be used for validations (see example above).
     *
     * @throws ParamDonoexException When param does not exist.
     * @throws ParamWrotypeException When param exists but is of wrong type.
     * @throws ParamIsEmptyException When param is empty while it shouldn't be.
     * @throws ConfigIncompleteException When any error occured.
     * @return boolean
     */
    public function validateParams(array $definitions) : bool
    {

        // Lvd.
        $parent = get_class($this->parentApp);

        // Try.
        try {

            // Check all configs.
            foreach ($definitions as $def) {

                // If param is not defined.
                if ($this->isParamSet($def[0]) === false) {
                    throw new ParamDonoexException([ $def[0], $parent ]);
                }

                // Lvd.
                $value = $this->getParam($def[0]);

                // Get type or class name of this variable.
                if (is_object($value) === true) {
                    $typeOrClass = get_class($value);
                } else {
                    $typeOrClass = gettype($value);
                }

                // If constant has wrong type.
                if (in_array($typeOrClass, explode('|', $def[1])) === false) {
                    throw new ParamWrotypeException([ $def[0], $def[1], $typeOrClass, $parent ]);
                }

                // If constant is obligatory not-empty, and in fact is empty.
                if ($def[2] === true && empty($value) === true) {
                    throw new ParamIsEmptyException([ $def[0], $parent ]);
                }
            }//end foreach
        } catch (Throwable $thr) {
            throw new ConfigIncompleteException([ $parent ], 0, $thr);
        }//end try

        return true;
    }
}
