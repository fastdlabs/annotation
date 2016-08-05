<?php

namespace FastD\Annotation;

use ReflectionClass;

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
        'variables' => 'FastD\Annotation\Types\Variable',
        'concrete' => 'FastD\Annotation\Types\Concrete',
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
     * Parser constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $reflectionClass = $this->getReflectionClass($class);

        $parents = $this->recursiveReflectionParent($reflectionClass);
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
     * @return void
     */
    protected function clearExtends()
    {
        $this->extends = [];
    }

    /**
     * @param $docComment
     * @return array
     */
    public function parseDocComment($docComment)
    {
        $annotations = [];

        foreach ($this->types as $name => $type) {
            $annotations[$name] = (new $type)->parse($docComment);
        }

        return $annotations;
    }
}