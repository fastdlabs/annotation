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
        include_once __DIR__ . '/../AnnotationsClasses/AnnotationArray.php';
        include_once __DIR__ . '/../AnnotationsClasses/AnnotationArrayExtends.php';
    }

    /**
     * Class IndexController
     *
     * @package                                   Tests\AnnotationsClasses
     *
     * @name foo
     * @json ["abc"]
     * @directive("test")
     * @route("/")
     * @Tests\AnnotationsClasses\AnnotationObject -> test()
     *
     * @route("/index")
     */
    public function testParentsAnnotation()
    {
        $parser = new ClassParser(IndexController::class);

        $annotations = $parser->getParentAnnotations();

        $this->assertEquals([
            [
                'functions' => [
                    'directive' => [
                        'test'
//                        'class' => IndexController::class,
//                        'method' => 'indexAction',
//                        'params' => [
//                            'test'
//                        ]
                    ],
                    'route' => [
                        '/'
//                        'class' => IndexController::class,
//                        'method' => 'indexAction',
//                        'params' => ['/']
                    ]
                ],
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'foo',
                    'json' => ['abc']
                ]
            ]
        ], $annotations);

        /**
         * Class ChildController
         *
         * @package Tests\AnnotationsClasses
         *
         * @name child
         * @json ["abc"]
         * @directive("/test")
         * @route("/child")
         */
        $parser = new ClassParser(ChildController::class);

        $annotations = $parser->getParentAnnotations();

        $this->assertEquals([
            [
                'functions' => [
                    'directive' => [
                        'test'
//                        'class' => ChildController::class,
//                        'method' => '',
//                        'params' => ['test']

                    ],
                    'route' => [
                        '/base'
//                        'class' => ChildController::class,
//                        'method' => '',
//                        'params' => ['/base']
                    ]
                ],
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'base',
                    'json' => ['abc']
                ]
            ],
            [
                'functions' => [
                    'directive' => ['test'],
                    'route' => ['/child']
                ],
                'variables' => [
                    'package' => 'Tests\AnnotationsClasses',
                    'name' => 'child',
                    'json' => ['abc']
                ]
            ]
        ], $annotations);
    }

    public function testEmptyParentsAnnotation()
    {
        $parser = new ClassParser(AnnotationDirective::class);

        $this->assertEquals([
            [
                'functions' => [],
                'variables' => [],
            ]
        ], $parser->getParentAnnotations());
    }

    public function testClassAnnotation()
    {
        $parser = new ClassParser(ChildController::class);

        $annotations = $parser->getClassAnnotations();

        $this->assertEquals([
            'functions' => [
                'directive' => [
                    'testtest'
                ],
                'route' => [
                    '/base/child'
                ],
            ],
            'variables' => [
                'package' => 'Tests\AnnotationsClasses',
                'name' => 'child',
                'json' => [
                    'abc'
                ]
            ]
        ], $annotations);
    }

    public function testClassArrayAnnotationMerge()
    {
        $parser = new ClassParser(AnnotationArrayExtends::class);

        $annotation = $parser->getClassAnnotations();

        $this->assertEquals(['functions' => [
            'test' => [
                'info' => [
                    'name' => 'jan',
                    'age' => 18
                ]
            ]
        ]], $annotation);
    }

    public function testMethodArrayAnnotationMerge()
    {
        $parser = new ClassParser(AnnotationArrayExtends::class);

        $annotation = $parser->getMethodAnnotation('testAction');

        $this->assertEquals($annotation, [
            'functions' => [
                'test' => [
                    'class' => AnnotationArrayExtends::class,
                    'method' => 'testAction',
                    'params' => [
                        'info' => [
                            'name' => 'jan',
                            'age' => 18,
                            'height' => 180
                        ]
                    ]
                ]
            ],
            'variables' => []
        ]);
    }

    public function testMethodAnnotation()
    {
        $parser = new ClassParser(ChildController::class);

        $annotations = $parser->getMethodAnnotations();

        $this->assertEquals([
            'indexAction' => [
                'functions' => [
                    'route' => [
                        'class' => ChildController::class,
                        'method' => 'indexAction',
                        'params' => [
                            '/base/child/index'
                        ]
                    ],
                ],
                'variables' => [
                    'name' => 'indexAction'
                ]
            ],
            'returnAction' => [
                'functions' => [
                    'route' => [
                        'class' => ChildController::class,
                        'method' => 'returnAction',
                        'params' => [
                            '/base/child/return'
                        ]
                    ],
                ],
                'variables' => [
                    'name' => 'returnAction'
                ]
            ],
        ], $annotations);
    }
}
