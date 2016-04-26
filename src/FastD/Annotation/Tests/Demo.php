<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/1
 * Time: 下午11:13
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */
class Demo 
{
    /**
     * @Route("/{name}", name="abc", defaults={"name": "jan"})
     * @Route(method=["POST"])
     */
    public function demoAction()
    {}

    /**
     * @Route("/{name}", name="abc", defaults={"name": "jan"}, format=["json", "php", "xml"])
     * @Route(requirements={"name":"\w+"}, method=["POST", "GET"])
     * @Methods(["GET", "POST"])
     */
    public function demoAction2()
    {}

}