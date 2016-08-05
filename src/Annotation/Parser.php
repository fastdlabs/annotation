<?php

namespace FastD\Annotation;

use FastD\Annotation\Types\Directive;
use FastD\Annotation\Types\Variable;
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
        'directives' => Directive::class,
        'variables' => Variable::class,
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

        print_r($parents);
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