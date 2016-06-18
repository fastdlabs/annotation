<?php

namespace FastD\Annotation;

use ReflectionClass;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
class AnnotatorClass extends Annotator
{
    /**
     * Annotator constructor.
     *
     * @param ReflectionClass $reflectionClass
     */
    public function __construct(ReflectionClass $reflectionClass)
    {
        $this->parameters = $this->parse($reflectionClass->getDocComment());

        $this->reflection = $reflectionClass;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->reflection->getName();
    }
}