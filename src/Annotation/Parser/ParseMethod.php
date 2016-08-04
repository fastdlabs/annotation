<?php

namespace FastD\Annotation\Parser;

use ReflectionMethod;

class ParseMethod extends Parser
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