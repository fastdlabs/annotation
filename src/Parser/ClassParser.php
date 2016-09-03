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