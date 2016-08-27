<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Annotation\Reader;

class ReaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/AnnotationsClasses/AnnotationDirective.php';
        include_once __DIR__ . '/AnnotationsClasses/AnnotationObject.php';
        include_once __DIR__ . '/AnnotationsClasses/BaseController.php';
        include_once __DIR__ . '/AnnotationsClasses/ChildController.php';
        include_once __DIR__ . '/AnnotationsClasses/IndexController.php';
    }

    public function testAnnotationReader()
    {
//        $reader = new Reader();
//
//        $annotation = $reader->getAnnotations(ChildController::class);
//
//        $this->assertEquals('child', $annotation->getVariable('name'));
//
//        $this->assertEquals('base', $annotation->parent()->getVariable('name'));
//
//        $this->assertEquals('method', $annotation->getMethod('indexAction')->getVariable('name'));
    }

    public function testDirectives()
    {
        include_once __DIR__ . '/functions.php';

        $reader = new Reader([
            'route' => function ($previous, $index, $value) {
                return $previous . $value;
            }
        ]);

        $annotation = $reader->getAnnotations(ChildController::class);

//        $routeResult = $annotation->callFunction('route');

//        $this->assertEquals('/base/child', $routeResult);

//        $routeResult = $annotation->getMethod('indexAction')->callFunction('route');

//        $this->assertEquals('/base/child/index', $routeResult);
    }
}

