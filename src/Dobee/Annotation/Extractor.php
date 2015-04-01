<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/1
 * Time: 下午11:06
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Annotation;

class Extractor extends \ReflectionClass
{
    private $methodsAnnotation;

    public function extractorAnnotation()
    {
        return array(
            'class'     => $this->extractClassAnnotation(),
            'methods'   => $this->extractMethodsAnnotation(),
        );
    }

    public function extractClassAnnotation()
    {
        return $this->getDocComment();
    }

    public function extractMethodsAnnotation()
    {
        if (null === $this->methodsAnnotation) {
            foreach ($this->getMethods() as $method) {
                $this->methodsAnnotation[$method->getName()] = $method->getDocComment();
            }
        }

        return $this->methodsAnnotation;
    }

    public function extractMethodAnnotation($method)
    {
        return $this->methodsAnnotation[$method];
    }
}