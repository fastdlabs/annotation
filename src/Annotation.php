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
use FastD\Annotation\Parser\ClassParser;

/**
 * Class Annotation
 *
 * @package FastD\Annotation
 */
class Annotation
{
    /**
     * @var ClassParser
     */
    protected $classParser;

    /**
     * @var array
     */
    protected $definitionFunctions = [];

    /**
     * Annotation constructor.
     *
     * @param $class
     * @param array $functions
     * @throws UndefinedAnnotationFunctionException
     */
    public function __construct($class, array $functions = [])
    {
        $this->classParser = new ClassParser($class);

        $this->definitionFunctions = $functions;

        $methodAnnotations = $this->classParser->getMethodAnnotations();

        foreach ($methodAnnotations as $key => $methodAnnotation) {
            $functions = isset($methodAnnotation['functions']) ? $methodAnnotation['functions'] : [];
            foreach ($functions as $name => $params) {
                if (isset($this->definitionFunctions[$name])) {
                    call_user_func_array($this->definitionFunctions[$name], $params);
                    continue;
                }

                if (function_exists($name)) {
                    call_user_func_array($name, $params);
                    continue;
                }

                throw new UndefinedAnnotationFunctionException($name);
            }
        }
    }

    /**
     * @return array
     */
    public function getMethodAnnotations()
    {
        return $this->classParser->getMethodAnnotations();
    }

    /**
     * @param $method
     * @return array
     */
    public function getMethodAnnotation($method)
    {
        return $this->classParser->getMethodAnnotation($method);
    }
}