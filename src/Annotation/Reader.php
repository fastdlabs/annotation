<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation;

class Reader
{
    protected $parser;

    public function __construct($class)
    {
        $this->parser = new Parser($class);

        $params = $this->parser->parse($class);
    }

    public function getAnnotations()
    {

    }
}
