<?php

namespace FastD\Annotation\Parser;

use FastD\Annotation\Types\Functions;
use FastD\Annotation\Types\Variable;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
class Parser implements ParseInterface
{
    /**
     * @var array
     */
    protected $types = [
        'functions' => Functions::class,
        'variables' => Variable::class,
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
     * @param $docComment
     * @return array
     */
    public function parse($docComment)
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
     * Recursive reflection.
     *
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    protected function recursiveReflectionParent(ReflectionClass $reflectionClass)
    {
        array_unshift($this->classAnnotations, $this->parse($reflectionClass->getDocComment()));

        if (false !== $reflectionClass->getParentClass()) {
            $this->recursiveReflectionParent($reflectionClass->getParentClass());
        }
    }
}