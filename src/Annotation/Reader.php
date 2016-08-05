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
     * @var Parser
     */
    protected $parser;

    protected $directives = [];

    /**
     * Reader constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $this->parser = new Parser($class);

        $this->parser->parseDocComment($class);
    }

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
     * @return Annotation
     */
    public function getClassAnnotations()
    {

    }
}
