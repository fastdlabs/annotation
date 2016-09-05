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
    protected $parents = [];

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
        array_unshift($this->parents, $this->parse($reflectionClass->getDocComment()));

        if (false !== $reflectionClass->getParentClass()) {
            $this->extendsParents($reflectionClass->getParentClass());
        }
    }

    /**
     * @return void
     */
    protected function mergeAnnotation()
    {
        $classAnnotations = $parser->getClassAnnotations();

        $this->methodAnnotations = $parser->getMethodAnnotations();

        $this->parentAnnotations = $classAnnotations;
        array_pop($this->parentAnnotations);

        $this->position = count($this->parentAnnotations);

        foreach ($classAnnotations as $classAnnotation) {
            $this->classAnnotations['functions'] = $this->mergeFunctions(isset($this->classAnnotations['functions']) ? $this->classAnnotations['functions'] : [], isset($classAnnotation['functions']) ? $classAnnotation['functions'] : []);
            $this->mergeVariables($classAnnotation);
        }

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
        return $this->parents;
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