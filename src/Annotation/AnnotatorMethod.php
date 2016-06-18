<?php

namespace FastD\Annotation;

use ReflectionMethod;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
class AnnotatorMethod extends Annotator
{
    /**
     * Annotator constructor.
     *
     * @param ReflectionMethod $reflectionMethod
     */
    public function __construct(ReflectionMethod $reflectionMethod)
    {
        $this->parameters = $this->parse($reflectionMethod->getDocComment());

        $this->reflection = $reflectionMethod;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->reflection->getDeclaringClass()->getName();
    }
}