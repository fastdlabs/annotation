<?php
use FastD\Annotation\Annotation;

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
        $annoatation = new Annotation(ChildController::class);

        $annoatation->executeFunctions([]);
    }

    public function testHasAnnotationClassFunctions()
    {
//        $this->expectOutputString('/base/child/index' . PHP_EOL . '/base/child/return' . PHP_EOL);

        new Annotation(ChildController::class, [
            'route' => function ($path) {
                echo $path . PHP_EOL;
            },
        ]);
    }

    public function testHasKeyParamsAnnotationFunctions()
    {
//        $this->expectOutputString(print_r([
//            'name' => 'jan',
//            'age' => 18,
//            'height' => 180
//        ], true));

        new Annotation(AnnotationArrayExtends::class, [
            'test' => function ($info) {
                print_r($info);
            },
        ]);
    }
}