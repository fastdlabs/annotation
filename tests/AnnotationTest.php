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
        $annotation = new Annotation(ChildController::class);

        $annotation->run([]);
    }

    public function testHasAnnotationClassFunctions()
    {
        $phpunit = $this;

//        $this->expectOutputString(sprintf(<<<EOF
//Class: ChildController
//Method: indexAction
//Params: %s
//EOF
//            , print_r(['/base/child/index'], true)));
//
//        $this->expectOutputString(sprintf(<<<EOF
//Class: ChildController
//Method: returnAction
//Params: %s
//EOF
//            , print_r(['/base/child/return'], true)));

        $annotation = new Annotation(ChildController::class);

        $annotation->run([
            'route' => function ($class, $method, $params) use ($phpunit) {
                echo sprintf("Class: %s \r\nMethod: %s \r\nParams: %s\r\n", $class, $method, print_r($params, true));
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

        $annotation = new Annotation(AnnotationArrayExtends::class);

        $annotation->run([
            'test' => function ($info) {
                print_r($info);
            },
        ]);
    }
}