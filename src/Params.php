<?php declare(strict_types=1);

namespace Przeslijmi\CliApp;

use Przeslijmi\CliApp\Exceptions\ParamDonoexException;

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
     * Constructor - defines default param 'config' and alias 'c'.
     *
     * @since v1.0
     */
    public function __construct()
    {

        $this->setParam('config', '');
        $this->setAliases('config', 'c');
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
     * @since  v1.0
     * @throws ParamDonoexException When no param with given name has been found.
     * @return mixed
     */
    public function getParam(string $name, bool $throw = true)
    {

        if (isset($this->params[$name]) === false && $throw === true) {
            throw new ParamDonoexException($name);
        } elseif (isset($this->params[$name]) === false && $throw === false) {
            return null;
        }

        return $this->params[$name];
    }

    /**
     * Returns name of operation to perform by CliApp.
     *
     * @since  v1.0
     * @return string
     */
    public function getOperation() : string
    {

        // Conclude.
        if ($this->operation === '?' || $this->operation === 'h') {
            $this->operation = 'help';
        }

        return $this->operation;
    }

    /**
     * Check if this param has been used (even if without value).
     *
     * @param string $name Name of the param to check.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isParamSet(string $name) : bool
    {

        return isset($this->params[$name]);
    }

    /**
     * Changes `argv` std php array into key/value array.
     *
     * @param array $params Contents of `argv` array.
     *
     * @since  v1.0
     * @return self
     */
    public function set(array $params) : self
    {

        // Reset.
        $this->params = [];

        // Shortcut - do nothing.
        if (count($params) < 2) {
            return $this;
        }

        // Save operation if it is given.
        if (substr($params[1], 0, 1) !== '-') {
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
     * @since  v1.0
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

        return $this;
    }

    /**
     * Setter for operation.
     *
     * @param string $operation Name of operation.
     *
     * @since  v1.0
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
     * @since  v1.0
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
}
