<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

/**
 * Class ChildController
 * @package Tests\AnnotationsClasses
 *
 * @name child
 * @json ["abc"]
 * @directive("test")
 * @route("/child")
 */
class ChildController extends BaseController
{
    /**
     * @name indexAction
     * @route("/index")
     */
    public function indexAction()
    {}

    /**
     * @name returnAction
     * @route("/return")
     */
    public function returnAction()
    {}
}
