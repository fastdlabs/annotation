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

use FastD\Annotation\Exceptions\UndefinedAnnotationFunctionException;
use Iterator;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class ClassParser
 *
 * @package FastD\Annotation\Parser
 */
class ClassParser extends Parser implements Iterator
{
    /**
     * @var string
     */
    protected $className;

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
     *
     * @param $class
     */
    public function __construct($class)
    {
        $reflectionClass = new ReflectionClass($class);

        $this->className = $reflectionClass->getName();

        $this->extendsParents($reflectionClass);

        $this->mergeClassAnnotations();

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (false !== $method->getDeclaringClass() && $method->getDeclaringClass()->getName() == $reflectionClass->getName()) {
                $this->methodAnnotations[$method->getName()] = $this->parse($method->getDocComment());
            }

            unset($method);
        }

        $this->mergeMethodAnnotations();

        unset($reflectionClass);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Recursive reflection.
     *
     * @param ReflectionClass $reflectionClass
     * @return void
     */
    protected function extendsParents(ReflectionClass $reflectionClass)
    {
        $annotation = $this->parse($reflectionClass->getDocComment());

        $annotation['class'] = $reflectionClass->getName();

        array_unshift($this->parentAnnotations, $annotation);

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
                if (isset($previous[$index])) {
                    if (is_array($value)) {
                        $parameters[$index] = array_merge($previous[$index], $value);
                    } else {
                        $parameters[$index] = $previous[$index] . $value;
                    }
                }
            }
            $this->classAnnotations['functions'][$name] = $parameters;
        }
    }

    /**
     * @return void
     */
    protected function mergeClassAnnotations()
    {
        foreach ($this->parentAnnotations as $classAnnotation) {
            if (isset($classAnnotation['functions'])) {
                $this->mergeFunctions($classAnnotation['functions']);
            }

            if (isset($classAnnotation['variables'])) {
                $this->mergeVariables($classAnnotation['variables']);
            }
        }

        $this->classAnnotations['class'] = $this->className;
    }

    /**
     * @return void
     */
    protected function mergeMethodAnnotations()
    {
        foreach ($this->methodAnnotations as $key => $methodAnnotation) {
            $functions = $methodAnnotation['functions'];
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
     * @return mixed
     * @throws UndefinedAnnotationFunctionException
     */
    public function getMethodAnnotation($name)
    {
        if (isset($this->methodAnnotations[$name])) {
            return $this->methodAnnotations[$name];
        }

        throw new UndefinedAnnotationFunctionException($name);
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->methodAnnotations);
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->methodAnnotations);
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->methodAnnotations);
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *        Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->methodAnnotations[$this->key()]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->methodAnnotations);
    }
}