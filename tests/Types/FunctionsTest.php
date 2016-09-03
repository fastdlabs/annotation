<?php
use FastD\Annotation\Types\Functions;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class FunctionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \FastD\Annotation\Types\TypesInterface
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new Functions();
    }

    public function testFunctions()
    {
        $docComment = <<<DOC
/**
 * @test("name")
 */
DOC;

        $functions = $this->parser->parse($docComment);

        $this->assertEquals(['test' => ['name']], $functions);
    }

    public function testKVFunctions()
    {
        $docComment = <<<DOC
/**
 * @test(age=18, json={"name": "janhuang"})
 */
DOC;

        $functions = $this->parser->parse($docComment);

        $this->assertEquals([
            'test' => [
                'age' => 18,
                'json' => [
                    'name' => 'janhuang'
                ]
            ]
        ], $functions);

        $docComment = <<<DOC
/**
 * @test("name", age=18, json={"name": "janhuang"}, info={"city": "CN"})
 */
DOC;

        $functions = $this->parser->parse($docComment);

        $this->assertEquals([
            'test' => [
                "name",
                'age' => 18,
                'json' => [
                    'name' => 'janhuang'
                ],
                "info" => [
                    "city" => "CN"
                ]
            ],
        ], $functions);
    }

    public function testEmptyFunctions()
    {
        $this->assertEmpty($this->parser->parse(''));
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
