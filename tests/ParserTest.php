<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Tests;

use FastD\Annotation\Parser;
use FastD\Annotation\Types\Concrete;
use FastD\Annotation\Types\Directive;
use FastD\Annotation\Types\Variable;
use Tests\AnnotationsClasses\BaseController;
use Tests\AnnotationsClasses\ChildController;
use Tests\AnnotationsClasses\IndexController;
use PHPUnit_Framework_TestCase;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseSyntax()
    {
        $parser = new Parser(IndexController::class);

        $this->assertEquals([
            IndexController::class => [
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'foo',
                    'json' => [
                        'abc',
                    ],
                ],
                'directives' => [
                    'directive' => [
                        '"test"'
                    ],
                    'route' => [
                        '"/"'
                    ]
                ]
            ],
        ], $parser->getClassAnnotations());
    }

    public function testHadParentParseSyntax()
    {
        $parser = new Parser(ChildController::class);

        $this->assertEquals([
            ChildController::class => [
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'child',
                    'json' => [
                        'abc',
                    ],
                ],
                'directives' => [
                    'directive' => [
                        '"test"'
                    ],
                    'route' => [
                        '"/"'
                    ]
                ]
            ],
            BaseController::class => [
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'base',
                    'json' => [
                        'abc',
                    ],
                ],
                'directives' => [
                    'directive' => [
                        '"test"'
                    ],
                    'route' => [
                        '"/"'
                    ]
                ]
            ]
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
        $parse = new Directive();

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

    public function testConcreteParseSyntax()
    {
        $parse = new Concrete();

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
    }
}

