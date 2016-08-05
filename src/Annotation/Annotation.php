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
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $directives = [];

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function get($name)
    {
        return $this->variables[$name] ?? false;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getDirective($name)
    {
        return $this->directives[$name] ?? false;
    }

    /**
     * @param $name
     * @param $parameters
     * @return $this
     */
    public function setDirective($name, $parameters)
    {
        $this->directives[$name] = $parameters;

        return $this;
    }
}