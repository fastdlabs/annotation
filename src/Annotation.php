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

use FastD\Annotation\Exceptions\UndefinedAnnotationFunctionException;

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

        $this->annotationVariables = isset($this->classAnnotations['variables']) ? $this->classAnnotations['variables'] : [];
        $this->annotationFunctions = isset($this->classAnnotations['functions']) ? $this->classAnnotations['functions'] : [];
    }

    /**
     * 合并注释变量
     *
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
     * 合并 (继承) 函数参数。
     *
     * 例如: @route("/name") @route("/test") => /name/test
     *
     * @param $functions
     * @param $parent
     * @return array
     */
    protected function mergeFunctions(array $functions, $parent)
    {
        foreach ($parent as $name => $parameters) {
            $previous = $functions[$name] ?? [];
            foreach ($parameters as $index => $value) {
                $parameters[$index] = (isset($previous[$index]) ?  $previous[$index] : '') . $value;
            }
            $functions[$name] = $parameters;
        }

        return $functions;
    }

    /**
     * @return array
     */
    public function getMethodAnnotations()
    {
        return $this->methodAnnotations;
    }

    /**
     * @param $method
     * @return array
     */
    public function getMethodAnnotation($method)
    {
        return [
            'variables' => isset($this->methodAnnotations[$method]['variables']) ? $this->methodAnnotations[$method]['variables'] : [],
            'functions' => isset($this->methodAnnotations[$method]['functions']) ? $this->methodAnnotations[$method]['functions'] : [],
        ];
    }

    /**
     * @return array
     */
    public function getMethodVariables()
    {
        return $this->annotationVariables;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getMethodVariable($name)
    {
        return isset($this->annotationVariables[$name]) ? $this->annotationVariables[$name] : false;
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

    /**
     * @param $name
     * @return mixed
     */
    public function getFunctionParams($name)
    {
        return isset($this->annotationFunctions[$name]) ? $this->annotationFunctions[$name] : false;
    }

    /**
     * @param $name
     * @param array $parameters
     * @return mixed
     * @throws UndefinedAnnotationFunctionException
     */
    public function callFunction($name, array $parameters = [])
    {
        $callable = isset($this->definitionFunctions[$name]) ? $this->definitionFunctions[$name] : false;

        if (isset($this->definitionFunctions[$name])) {
            return call_user_func_array($callable, $parameters);
        }

        if (function_exists($name)) {
            return call_user_func_array($name, $parameters);
        }

        throw new UndefinedAnnotationFunctionException($name);
    }
}