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
 *
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
    protected $functions = [];

    /**
     * Reader constructor.
     * @param array $functions
     */
    public function __construct(array $functions = [])
    {
        $this->setFunctions($functions);
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setFunction($name, $value)
    {
        $this->functions[$name] = $value;

        return $this;
    }

    /**
     * @param array $functions
     * @return $this
     */
    public function setFunctions(array $functions)
    {
        $this->functions = $functions;

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasFunction($name)
    {
        return isset($this->functions[$name]);
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getFunction($name)
    {
        if (!$this->hasFunction($name)) {
            return false;
        }

        return $this->functions[$name];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * @param $class
     * @return Annotation
     */
    public function getAnnotations($class)
    {
        if (!isset($this->annotations[$class])) {
            $this->annotations[$class] = new Annotation(new Parser($class), $this->functions);
        }

        return $this->annotations[$class];
    }
}
