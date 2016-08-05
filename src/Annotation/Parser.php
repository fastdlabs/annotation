<?php

namespace FastD\Annotation;

use ReflectionClass;
use ReflectionMethod;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
class Parser
{
    /**
     * @var array
     */
    protected $types = [
        'directives' => 'FastD\Annotation\Types\Directive',
        'variables' => 'FastD\Annotation\Types\Variable'
    ];

    /**
     * @var array
     */
    protected $classAnnotations = [];

    /**
     * @var array
     */
    protected $methodAnnotations = [];

    /**
     * Parser constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $reflectionClass = new ReflectionClass($class);

        $this->recursiveReflectionParent($reflectionClass);

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (false !== $method->getDeclaringClass() && $method->getDeclaringClass()->getName() == $reflectionClass->getName()) {
                $this->methodAnnotations[$method->getName()] = $this->parseDocComment($method->getDocComment());
            }

            unset($method);
        }

        unset($reflectionClass);
    }

    /**
     * Recursive reflection.
     *
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    protected function recursiveReflectionParent(ReflectionClass $reflectionClass)
    {
        array_unshift($this->classAnnotations, $this->parseDocComment($reflectionClass->getDocComment()));

        if (false !== $reflectionClass->getParentClass()) {
            $this->recursiveReflectionParent($reflectionClass->getParentClass());
        }
    }

    /**
     * @param $docComment
     * @return array
     */
    public function parseDocComment($docComment)
    {
        if (!$docComment) {
            return [];
        }

        $annotations = [];

        foreach ($this->types as $name => $type) {
            $annotations[$name] = (new $type)->parse($docComment);
        }

        return $annotations;
    }

    /**
     * @return array
     */
    public function getClassAnnotations()
    {
        return $this->classAnnotations;
    }

    /**
     * @return array
     */
    public function getMethodAnnotations()
    {
        return $this->methodAnnotations;
    }
}