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

class AnnotationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('\Demo')) {
            include __DIR__ . '/Demo.php';
        }
    }

    public function testParse()
    {
        $annotation = new Annotation(\Demo::class);

        $this->assertEquals('demoAction', $annotation->getMethods()[0]->getName());
        $this->assertEquals('demoAction2', $annotation->getMethods()[1]->getName());

        $this->assertEquals([
            '/{name}',
            'name' => 'abc',
            'defaults' => [
                'name' => 'jan',
            ],
            'method' => [
                'POST'
            ]
        ], $annotation->getMethods()[0]->getParameters('Route'));
    }
}
