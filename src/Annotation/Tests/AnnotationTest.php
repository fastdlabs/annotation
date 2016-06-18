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
    /**
     * @Route("/cover")
     */
    public function testCoverAction()
    {

    }
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
class TestExtendParent extends TestParent
{
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
class TestExtendGrandParent extends TestParentExtend
{
    /**
     * @Route("/", name="test")
     */
    public function testAction()
    {

    }
}

class AnnotationTest extends \PHPUnit_Framework_TestCase
{
    public function testNotExtends()
    {
        $annotation = new Annotation(Test::class);

        $this->assertEquals([
            "/self/",
            "name" => 'test'
        ], $annotation->getAnnotator('testAction')->getParameters()['Route']);
    }

    public function testOnceExtends()
    {
        $annotation = new Annotation(TestExtendParent::class);

        $this->assertEquals([
            '/parent/extend/',
            'methods' => ['get', 'post'],
            'name' => 'test',
        ], $annotation->getAnnotator('testAction')->getParameters()['Route']);
    }

    public function testExtendCover()
    {
        $annotation = new Annotation(TestExtendParent::class);

        $this->assertEquals([
            '/parent/extend/',
            'methods' => ['get', 'post'],
            'name' => 'test',
        ], $annotation->getAnnotator('testAction')->getParameters()['Route']);

        $this->assertEquals(1, $annotation->count());
    }

    public function testMultiExtends()
    {
        $annotation = new Annotation(TestExtendGrandParent::class);

        $this->assertEquals([
            '/grand/extendGrand/ex/grand/',
            'methods' => ['get', 'post'],
            'name' => 'test'
        ], $annotation->getAnnotator('testAction')->getParameters()['Route']);

        $this->assertEquals([
            '::1'
        ], $annotation->getAnnotator('testAction')->getParameters()['Host']);

        $this->assertEquals([
            'Route' => [
                '/grand/extendGrand/ex/grand/',
                'methods' => ['get', 'post'],
                'name' => 'test'
            ],
            'Host' => ['::1']
        ], $annotation->getAnnotator('testAction')->getParameters());
    }

    public function testCoverExtendMethod()
    {

    }
}
