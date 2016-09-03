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

    public function testDirectives()
    {
        $reader = new Reader([
            'route' => function ($path) {

            },
            'directive' => function ($name) {
                return $name;
            }
        ]);

        $annotation = $reader->getAnnotations(ChildController::class);

        $this->assertEquals([
            'name' => 'indexAction',
        ], $annotation->getMethodAnnotation('indexAction')['variables']);

        $this->assertEquals([
            'route' => ['/base/child/index'],
        ], $annotation->getMethodAnnotation('indexAction')['functions']);
    }
}

