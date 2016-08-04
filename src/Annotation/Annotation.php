<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation;

/**
 * Class Annotation
 *
 * @package FastD\Annotation
 */
class Annotation
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $functions;

    /**
     * Annotation constructor.
     *
     * @param $class
     * @param $method
     * @param array $functions
     */
    public function __construct($class, $method, array $functions = [])
    {
        $this->class = $class;

        $this->method = $method;

        $this->functions = $functions;
    }

    /**
     * @param $name
     * @return mixed
     * @throws InvalidFunctionException
     */
    public function callFunc($name)
    {
        if (!isset($this->functions[$name]) || !function_exists($name)) {
            throw new InvalidFunctionException($name);
        }

        return call_user_func_array($name, $this->functions[$name]);
    }
}