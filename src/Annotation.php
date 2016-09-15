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
use Iterator;

/**
 * Class Annotation
 *
 * @package FastD\Annotation
 */
class Annotation implements Iterator
{
    /**
     * @var ClassParser
     */
    protected $classParser;

    /**
     * Annotation constructor.
     *
     * @param $class
     */
    public function __construct($class)
    {
        $this->classParser = new ClassParser($class);
    }

    /**
     * @param array $definition
     * @throws UndefinedAnnotationFunctionException
     */
    public function executeFunctions(array $definition = [])
    {
        $methodAnnotations = $this->classParser->getMethodAnnotations();

        foreach ($methodAnnotations as $key => $methodAnnotation) {
            $functions = isset($methodAnnotation['functions']) ? $methodAnnotation['functions'] : [];
            foreach ($functions as $name => $params) {
                if (isset($definition[$name])) {
                    call_user_func_array($definition[$name], $params);
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

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        // TODO: Implement current() method.
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
        // TODO: Implement next() method.
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
        // TODO: Implement key() method.
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
        // TODO: Implement valid() method.
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
        // TODO: Implement rewind() method.
}}