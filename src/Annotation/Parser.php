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
    protected $extends = [];

    /**
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * @var Annotation
     */
    protected $annotation;

    /**
     * Parser constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $this->annotation = new Annotation();

        $reflectionClass = $this->getReflectionClass($class);

        $this->recursiveReflectionParent($reflectionClass);

        foreach ($this->extends as $extend) {
            $this->appendAnnotation($extend);
        }

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $annotation = $this->parseDocComment($method->getDocComment());
            $this->appendAnnotation($annotation);
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
        array_push($this->extends, $this->parseDocComment($reflectionClass->getDocComment()));

        if (false !== $reflectionClass->getParentClass()) {
            $this->recursiveReflectionParent($reflectionClass->getParentClass());
        }

        return $this->extends;
    }

    /**
     * @param array $annotations
     */
    protected function appendAnnotation(array $annotations)
    {
        if (isset($annotations['variables'])) {
            foreach ($annotations['variables'] as $name => $value) {
                $this->annotation->set($name, $value);
            }
        }
        if (isset($annotations['directives'])) {
            foreach ($annotations['directives'] as $name => $value) {
                $this->annotation->setDirective($name, $value);
            }
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
     * @return Annotation
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
}