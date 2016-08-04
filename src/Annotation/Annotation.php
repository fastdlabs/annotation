<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/26
 * Time: 上午11:36
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Annotation;

use FastD\Annotation\Parser\ParseClass;
use FastD\Annotation\Parser\ParseMethod;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class Annotation
 *
 * @package FastD\Annotation
 */
class Annotation
{
    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var AnnotationFunction[]
     */
    protected $annotations = [];

    /**
     * @var string
     */
    protected $filter;

    /**
     * Annotation constructor.
     *
     * @param $class
     * @param string $filter
     */
    public function __construct($class, $filter = null)
    {
        $this->filter = $filter;

        $this->annotations = $this->parse($class);
    }

    /**
     * @param $class
     * @return array
     */
    protected function parse($class)
    {
        $annotations = [];

        $class = new ReflectionClass($class);

        $parents = $this->recursiveReflectionParent($class);

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (false !== $method->getDeclaringClass() && $method->getDeclaringClass()->getName() == $class->getName()) {
                if (null !== $this->filter && false === strpos($method->getName(), $this->filter)) {
                    continue;
                }
                $annotator = new ParseMethod($method);
                $annotator = $this->merge($annotator, $parents);
                $annotations[$annotator->getName()] = $annotator;
            }
        }

        $this->resetWith();

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
        array_push($this->with, new ParseClass($reflectionClass));

        if (false !== $reflectionClass->getParentClass()) {
            $this->recursiveReflectionParent($reflectionClass->getParentClass());
        }

        return $this->with;
    }

    /**
     * @param ParseMethod $annotatorMethod
     * @param array $parents
     * @return ParseMethod
     */
    protected function merge(ParseMethod $annotatorMethod, array $parents = [])
    {
        $parameters = $annotatorMethod->getParameters();

        foreach ($parents as $parent) {
            if ($parent->isEmpty()) {
                continue;
            }
            $params = $parent->getParameters();
            foreach ($params as $key => $value) {
                if (isset($parameters[$key])) {
                    foreach ($value as $name => $item) {
                        if (isset($parameters[$key][$name])) {
                            if (is_string($item)) {
                                $parameters[$key][$name] = $item . $parameters[$key][$name];
                            } else if (is_array($item)) {
                                $parameters[$key][$name] = array_unique(array_merge($item, $$parameters[$key][$name]));
                            }
                        } else {
                            $parameters[$key][$name] = $item;
                        }
                    }
                } else {
                    $parameters[$key] = $value;
                }
            }
        }

        $annotatorMethod->setParameters($parameters);

        return $annotatorMethod;
    }

    /**
     * @return void
     */
    public function resetWith()
    {
        $this->with = [];
    }
}