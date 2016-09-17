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

    public function testUndefinedAnnotationClassFunctions()
    {
        $annotation = new Annotation(ChildController::class);
    }
}