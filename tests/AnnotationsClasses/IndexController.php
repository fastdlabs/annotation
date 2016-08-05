<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Tests\AnnotationsClasses;

/**
 * Class IndexController
 * @package Tests\AnnotationsClassess
 *
 * @name foo
 * @json ["abc"]
 * @directive("test")
 * @Tests\AnnotationsClasses\AnnotationObject -> test()
 */
class IndexController
{
    public function indexAction()
    {}
}
