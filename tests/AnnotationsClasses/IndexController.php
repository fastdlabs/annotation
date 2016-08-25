<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

/**
 * Class IndexController
 * @package Tests\AnnotationsClasses
 *
 * @name foo
 * @json ["abc"]
 * @directive("test")
 * @route("/")
 * @Tests\AnnotationsClasses\AnnotationObject -> test()
 */
class IndexController
{
    /**
     * @route("/index")
     */
    public function indexAction()
    {}
}
