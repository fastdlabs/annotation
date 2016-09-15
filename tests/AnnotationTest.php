<?php
use FastD\Annotation\Annotation;

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

class AnnotationTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/AnnotationsClasses/AnnotationDirective.php';
        include_once __DIR__ . '/AnnotationsClasses/AnnotationObject.php';
        include_once __DIR__ . '/AnnotationsClasses/BaseController.php';
        include_once __DIR__ . '/AnnotationsClasses/ChildController.php';
        include_once __DIR__ . '/AnnotationsClasses/IndexController.php';
        include_once __DIR__ . '/AnnotationsClasses/AnnotationArray.php';
        include_once __DIR__ . '/AnnotationsClasses/AnnotationArrayExtends.php';
    }

    /**
     * @expectedException \FastD\Annotation\Exceptions\UndefinedAnnotationFunctionException
     */
    public function testUndefinedAnnotationClassFunctions()
    {
        new Annotation(ChildController::class);
    }

    public function testHasAnnotationClassFunctions()
    {
        $this->expectOutputString('/base/child/index' . PHP_EOL . '/base/child/return' . PHP_EOL);

        new Annotation(ChildController::class, [
            'route' => function ($path) {
                echo $path . PHP_EOL;
            },
        ]);
    }

    public function testHasKeyParamsAnnotationFunctions()
    {
        $this->expectOutputString(print_r([
            'name' => 'jan',
            'age' => 18,
            'height' => 180
        ], true));

        new Annotation(AnnotationArrayExtends::class, [
            'test' => function ($info) {
                print_r($info);
            },
        ]);
    }
}