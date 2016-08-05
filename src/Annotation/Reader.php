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
     * @return Annotation
     */
    public function getAnnotations()
    {
        return $this->parser->getAnnotation();
    }
}
