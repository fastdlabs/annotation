<?php
use FastD\Annotation\Parser\ClassParser;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class ClassParserTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/../AnnotationsClasses/AnnotationDirective.php';
        include_once __DIR__ . '/../AnnotationsClasses/AnnotationObject.php';
        include_once __DIR__ . '/../AnnotationsClasses/BaseController.php';
        include_once __DIR__ . '/../AnnotationsClasses/ChildController.php';
        include_once __DIR__ . '/../AnnotationsClasses/IndexController.php';
    }

    /**
     * Class IndexController
     * @package Tests\AnnotationsClasses
     *
     * @name foo
     * @json ["abc"]
     * @directive("test")
     * @route("/")
     * @Tests\AnnotationsClasses\AnnotationObject -> test()
     *
     * @route("/index")
     */
    public function testClassAnnotation()
    {
        $parser = new ClassParser(IndexController::class);

        $annotations = $parser->getClassAnnotations();
        print_r($annotations);
        $this->assertEquals([
            'variables' => [
                'name' => 'foo',
                'json' => ['abc'],
            ],
            'functions' => [
                'directive' => ['test'],
                'route' => ['/'],
            ]
        ], $annotations);
    }
}
