<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation;

/**
 * Class Reader
 * @package FastD\Annotation
 */
class Reader
{
    /**
     * @var Annotation[]
     */
    protected $annotations = [];

    /**
     * @var array
     */
    protected $directives = [];

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setDirective($name, $value)
    {
        $this->directives[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasDirective($name)
    {
        return isset($this->directives[$name]);
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getDirective($name)
    {
        if (!$this->hasDirective($name)) {
            return false;
        }

        return $this->directives[$name];
    }

    /**
     * @param $class
     * @return Annotation
     */
    public function getAnnotations($class)
    {
        if (!isset($this->annotations[$class])) {
            $this->annotations[$class] = new Annotation(new Parser($class));
        }

        return $this->annotations[$class];
    }
}
