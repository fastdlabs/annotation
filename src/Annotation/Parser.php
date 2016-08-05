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
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * Parser constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $reflectionClass = $this->getReflectionClass($class);

        $this->recursiveReflectionParent($reflectionClass);

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (false !== $method->getDeclaringClass() && $method->getDeclaringClass()->getName() == $reflectionClass->getName()) {
                $this->methodAnnotations[$method->getName()] = $this->parseDocComment($method->getDocComment());
            }
        }
    }

    /**
     * @param $class
     * @return ReflectionClass
     */
    protected function getReflectionClass($class)
    {
        if (null === $this->reflection) {
            $this->reflection = new ReflectionClass($class);
        }

        return $this->reflection;
    }

    /**
     * Recursive reflection.
     *
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    protected function recursiveReflectionParent(ReflectionClass $reflectionClass)
    {
        $this->classAnnotations[$reflectionClass->getName()] = $this->parseDocComment($reflectionClass->getDocComment());

        if (false !== $reflectionClass->getParentClass()) {
            $this->recursiveReflectionParent($reflectionClass->getParentClass());
        }
    }

    protected function merge()
    {
        $parameters = $annotatorMethod->getParameters();

        foreach ($parents as $parent) {
            if ($parent->isEmpty()) {
                continue;
            }
            $params = $parent->getParameters();
            foreach ($params as $key => $value) {
                if (isset($parameters[$key])) {
                    foreach ($value as $name => $item) {
                        if (isset($parameters[$key][$name])) {
                            if (is_string($item)) {
                                $parameters[$key][$name] = $item . $parameters[$key][$name];
                            } else if (is_array($item)) {
                                $parameters[$key][$name] = array_unique(array_merge($item, $$parameters[$key][$name]));
                            }
                        } else {
                            $parameters[$key][$name] = $item;
                        }
                    }
                } else {
                    $parameters[$key] = $value;
                }
            }
        }

        $annotatorMethod->setParameters($parameters);

        return $annotatorMethod;
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