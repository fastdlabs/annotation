<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation;

use RuntimeException;

/**
 * Class Annotation
 *
 * @package FastD\Annotation
 */
class Annotation
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
     * @var array
     */
    protected $annotationVariables = [];

    /**
     * @var array
     */
    protected $annotationFunctions = [];

    protected $annotationFunctionResults = [];

    /**
     * @var array
     */
    protected $definitionFunctions = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * Annotation constructor.
     *
     * @param Parser $parser
     * @param array $functions
     */
    public function __construct(Parser $parser, array $functions = [])
    {
        $this->definitionFunctions = $functions;

        $classAnnotations = $parser->getClassAnnotations();

        $this->methodAnnotations = $parser->getMethodAnnotations();

        $this->parentAnnotations = $classAnnotations;
        array_pop($this->parentAnnotations);

        $this->position = count($this->parentAnnotations);

        foreach ($classAnnotations as $classAnnotation) {
            $this->classAnnotations['functions'] = $this->mergeAnnotationFunctions($this->classAnnotations['functions'] ?? [], $classAnnotation);
            $this->mergeVariables($classAnnotation);
        }

        foreach ($this->methodAnnotations as $key => $methodAnnotation) {
            $this->methodAnnotations[$key]['functions'] = $this->mergeAnnotationFunctions($this->classAnnotations['functions'], $methodAnnotation);
        }

        $this->annotationVariables = isset($this->classAnnotations['variables']) ? $this->classAnnotations['variables'] : [];
        $this->annotationFunctions = isset($this->classAnnotations['functions']) ? $this->classAnnotations['functions'] : [];
    }

    /**
     * @param $parent
     */
    protected function mergeVariables($parent)
    {
        if (isset($parent['variables'])) {
            foreach ($parent['variables'] as $name => $variable) {
                $this->classAnnotations['variables'][$name] = $variable;
            }
        }
    }

    /**
     * @return array
     */
    public function getMethodAnnotations()
    {
        return $this->methodAnnotations;
    }

    /**
     * @param $functions
     * @param $parent
     * @return array
     */
    protected function mergeAnnotationFunctions(array $functions, $parent)
    {
        if (isset($parent['functions'])) {
            foreach ($parent['functions'] as $name => $parameters) {
                if (isset($this->definitionFunctions[$name]) && is_callable($this->definitionFunctions[$name])) {
                    $previous = $functions[$name] ?? [];
                    foreach ($parameters as $index => $value) {
                        $parameters[$index] = $this->definitionFunctions[$name]($previous[$index] ?? '', $index, $value);
                    }
                }
                $functions[$name] = $parameters;
            }
        }

        return $functions;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getVariable($name)
    {
        return isset($this->annotationVariables[$name]) ? $this->annotationVariables[$name] : false;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getFunction($name)
    {
        return isset($this->annotationFunctions[$name]) ? $this->annotationFunctions[$name] : false;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws RuntimeException
     */
    public function callFunction($name, array $arguments = [])
    {
        if (false === ($parameters = $this->getFunction($name))) {
            throw new RuntimeException(sprintf('Annotation function "%s" is undefined.', $name));
        }

        if (!empty($arguments)) {
            $parameters = $arguments;
        }

        $callable = isset($this->definitionFunctions[$name]) ? $this->definitionFunctions[$name] : false;

        if (false === $callable && !function_exists($name)) {
            throw new RuntimeException(sprintf('Function "%s" is undefined.', $name));
        }

        if (is_callable($callable)) {
            return call_user_func_array($callable, $parameters);
        }

        return call_user_func_array($name, $parameters);
    }

    /**
     * @param $method
     * @return $this
     */
    public function getMethod($method)
    {
        $this->annotationVariables = isset($this->methodAnnotations[$method]['variables']) ? $this->methodAnnotations[$method]['variables'] : [];
        $this->annotationFunctions = isset($this->methodAnnotations[$method]['functions']) ? $this->methodAnnotations[$method]['functions'] : [];

        return $this;
    }

    /**
     * @return $this
     */
    public function parent()
    {
        $this->position--;

        if (isset($this->parentAnnotations[$this->position])) {
            $this->annotationVariables = isset($this->parentAnnotations[$this->position]['variables']) ? $this->parentAnnotations[$this->position]['variables'] : [];
            $this->annotationFunctions = isset($this->parentAnnotations[$this->position]['functions']) ? $this->parentAnnotations[$this->position]['functions'] : [];
        }

        return $this;
    }
}