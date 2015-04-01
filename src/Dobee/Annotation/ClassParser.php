<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/1
 * Time: 下午11:04
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Annotation;

/**
 * Class ClassParser
 *
 * @package Dobee\Annotation
 */
class ClassParser
{
    /**
     * @param string $class
     * @return Extractor
     */
    public static function getExtractor($class)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" is not exists.', $class));
        }

        return new Extractor($class);
    }
}