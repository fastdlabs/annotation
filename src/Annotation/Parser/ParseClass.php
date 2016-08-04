<?php

namespace FastD\Annotation\Parser;

use ReflectionClass;

class ParseClass extends Parser
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