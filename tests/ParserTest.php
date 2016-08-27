<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Annotation\Parser;
use FastD\Annotation\Types\Functions;
use FastD\Annotation\Types\Variable;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/AnnotationsClasses/AnnotationDirective.php';
        include_once __DIR__ . '/AnnotationsClasses/AnnotationObject.php';
        include_once __DIR__ . '/AnnotationsClasses/BaseController.php';
        include_once __DIR__ . '/AnnotationsClasses/ChildController.php';
        include_once __DIR__ . '/AnnotationsClasses/IndexController.php';
    }

    public function testParseSyntax()
    {
        $parser = new Parser(IndexController::class);

        $this->assertEquals([
            [
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'foo',
                    'json' => [
                        'abc',
                    ],
                ],
                'functions' => [
                    'directive' => [
                        'test'
                    ],
                    'route' => [
                        '/'
                    ]
                ]
            ],
        ], $parser->getClassAnnotations());
    }

    public function testHadParentParseSyntax()
    {
        $parser = new Parser(ChildController::class);

        $this->assertEquals([
            [
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'base',
                    'json' => [
                        'abc',
                    ],
                ],
                'functions' => [
                    'directive' => [
                        'test'
                    ],
                    'route' => [
                        '/base'
                    ]
                ]
            ],
            [
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'child',
                    'json' => [
                        'abc',
                    ],
                ],
                'functions' => [
                    'directive' => [
                        '/test'
                    ],
                    'route' => [
                        '/child'
                    ]
                ]
            ],
        ], $parser->getClassAnnotations());
    }

    public function testVariableParseSyntax()
    {
        $parse = new Variable();

        $annotation = $parse->parse(<<<EOF
/**
 * Class IndexController
 * @package Tests\AnnotationsClasses
 *
 * @name foo
 * @json ["abc"]
 * @directive("test")
 * @Tests\AnnotationsClasses\AnnotationObject -> test()
 */
EOF
);

        $this->assertEquals([
            'package' => 'Tests\AnnotationsClasses',
            'name' => 'foo',
            'json' => [
                'abc'
            ]
        ], $annotation);
    }

    public function testDirectiveParseSyntax()
    {
        $parse = new Functions();

        $annotation = $parse->parse(<<<EOF
/**
 * Class IndexController
 * @package Tests\AnnotationsClasses
 *
 * @name foo
 * @json ["abc"]
 * @directive(a, b)
 * @directive2(a, b)
 * @Tests\AnnotationsClasses\AnnotationObject -> test()
 */
EOF
);

        $this->assertEquals([
            'directive' => [
                'a', 'b'
            ],
            'directive2' => [
                'a', 'b'
            ],
        ], $annotation);
    }
}

