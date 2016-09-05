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

/**
 * Class ClassParser
 *
 * @package FastD\Annotation\Parser
 */
class ClassParser extends Parser
{
    /**
     * @var array
     */
    protected $parentAnnotations = [];

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

        $this->extendsParents($reflectionClass);

        $this->mergeClassAnnotation();

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
     * @return void
     */
    protected function extendsParents(ReflectionClass $reflectionClass)
    {
        array_unshift($this->parentAnnotations, $this->parse($reflectionClass->getDocComment()));

        if (false !== $reflectionClass->getParentClass()) {
            $this->extendsParents($reflectionClass->getParentClass());
        }
    }

    /**
     * @param $variables
     * @return void
     */
    protected function mergeVariables(array $variables)
    {
        foreach ($variables as $name => $variable) {
            $this->classAnnotations['variables'][$name] = $variable;
        }
    }

    /**
     * @param $functions
     * @return void
     */
    protected function mergeFunctions(array $functions)
    {
        foreach ($functions as $name => $parameters) {
            $previous = isset($this->classAnnotations['functions'][$name]) ? $this->classAnnotations['functions'][$name] : [];
            foreach ($parameters as $index => $value) {
                $parameters[$index] = (isset($previous[$index]) ? $previous[$index] : '') . $value;
            }
            $this->classAnnotations['functions'][$name] = $parameters;
        }
    }

    /**
     * @return void
     */
    protected function mergeClassAnnotation()
    {
        $position = count($this->parentAnnotations);

        foreach ($this->parentAnnotations as $classAnnotation) {
            $this->mergeFunctions($classAnnotation['functions']);
            $this->mergeVariables($classAnnotation['variables']);
        }
        return;
        foreach ($this->methodAnnotations as $key => $methodAnnotation) {
            $functions = isset($methodAnnotation['functions']) ? $methodAnnotation['functions'] : [];
            foreach ($functions as $name => $params) {
                if (isset($this->classAnnotations['functions'][$name])) {
                    foreach ($params as $index => $value) {
                        if (isset($this->classAnnotations['functions'][$name][$index])) {
                            if (is_array($value)) {
                                $params[$index] = array_merge($this->classAnnotations['functions'][$name][$index], $params[$index]);
                            } else {
                                $params[$index] = $this->classAnnotations['functions'][$name][$index] . $params[$index];
                            }
                        }
                    }
                    $functions[$name] = $params;
                }
            }
            $this->methodAnnotations[$key]['functions'] = $functions;
        }
    }

    /**
     * @return array
     */
    public function getParentAnnotations()
    {
        return $this->parentAnnotations;
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

    /**
     * @param $name
     * @return bool
     */
    public function getMethodAnnotation($name)
    {
        return isset($this->methodAnnotations[$name]) ? $this->methodAnnotations[$name] : false;
    }
}