<?php
use FastD\Annotation\Types\Variable;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class VariableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \FastD\Annotation\Types\TypesInterface
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new Variable();
    }

    public function testVariableParse()
    {
        $docComment = <<<DOC
/**
 * @name janhuang
 */
DOC;
;
        $variables = $this->parser->parse($docComment);

        $this->assertEquals(['name' => 'janhuang'], $variables);
    }

    public function testEmptyVariables()
    {
        $this->assertEmpty($this->parser->parse(''));
    }

    public function testJsonVariables()
    {
        $docComment = <<<DOC
/**
 * @name janhuang
 * @info {"name": "jan"}
 */
DOC;
        ;

        $variables = $this->parser->parse($docComment);

        $this->assertEquals([
            'name' => 'janhuang',
            'info' => [
                'name' => 'jan'
            ]
        ], $variables);
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
}
