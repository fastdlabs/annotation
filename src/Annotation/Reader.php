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

    protected $class;

    public function __construct($class)
    {
        $this->parser = new Parser();

        $this->class = $class;
    }
}
