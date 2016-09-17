<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation\Exceptions;

/**
 * Class UndefinedAnnotationVariableException
 *
 * @package FastD\Annotation\Exceptions
 */
class UndefinedAnnotationVariableException extends AnnotationException
{
    /**
     * UndefinedAnnotationVariableException constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct(sprintf('Annotation variable "%s" is undefined.', $name));
    }
}