<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/26
 * Time: 下午3:14
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Annotation\Tests;

use FastD\Annotation\Annotation;

/**
 * @Route("/grand")
 * @Host("::1")
 */
class TestGrandParent
{

}

/**
 * @Route("/extendGrand", methods=["get", "post"])
 */
class TestParentExtend extends TestGrandParent
{

}

/**
 * @Route("/parent", methods=["get", "post"])
 */
class TestParent
{

}

/**
 * @Route("/self")
 */
class Test
{
    /**
     * @Route("/", name="test")
     */
    public function testAction()
    {

    }
}

/**
 * @Route("/extend")
 */
class TestExtendParent extends TestParent {
    /**
     * @Route("/", name="test")
     */
    public function testAction()
    {

    }
}

/**
 * @Route("/ex/grand")
 */
class TestExtendGrandParent extends TestParentExtend {
    /**
     * @Route("/", name="test")
     */
    public function testAction()
    {

    }
}

class AnnotationTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $annotation = new Annotation(Test::class);

        print_r($annotation);
    }
}
