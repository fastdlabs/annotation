<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation\Parser;

use ReflectionClass;
use ReflectionMethod;

class ClassParser extends Parser
{
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
                $this->methodAnnotations[$method->getName()] = $this->parse($method->getDocComment());
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
    protected function extendsParent(ReflectionClass $reflectionClass)
    {
        array_unshift($this->classAnnotations, $this->parse($reflectionClass->getDocComment()));

        if (false !== $reflectionClass->getParentClass()) {
            $this->extendsParent($reflectionClass->getParentClass());
        }
    }

    /**
     * @return array
     */
    public function getClassAnnotations()
    {
        return $this->classAnnotations;
    }

    /**
     * @param $name
     * @return bool
     */
    public function getMethodAnnotation($name)
    {
        return isset($this->methodAnnotations[$name]) ? $this->methodAnnotations[$name] : false;
    }

    /**
     * @return array
     */
    public function getMethodAnnotations()
    {
        return $this->methodAnnotations;
    }
}