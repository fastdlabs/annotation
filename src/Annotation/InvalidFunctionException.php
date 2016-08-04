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

use Exception;

class InvalidFunctionException extends Exception
{
    public function __construct($name)
    {
        parent::__construct(sprintf('Annotation function "%s" is undefined.'));
    }
}