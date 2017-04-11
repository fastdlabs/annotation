<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Annotation\Parser\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseSyntax()
    {
        $parser = new Parser();

        $this->assertEquals([
            'variables' => [
                'name' => 'indexAction',
            ],
            'functions' => [
                'route' => [
                    '/index'
                ]
            ]
        ], $parser->parse(<<<DOC
/**
 * @name indexAction
 * @route("/index")
 */
DOC
        ));
    }

    public function testVariablesGetting()
    {
        $parser = new Parser();

        $parser->parse(<<<EOF
/**
 * @name indexAction
 * @route("/index")
 */
EOF
);
        $this->assertEquals($parser->get('name'), 'indexAction');
    }

    public function testFunctionArgumentsGetting()
    {
        $parser = new Parser();

        $parser->parse(<<<EOF
/**
 * @name indexAction
 * @route("/index")
 */
EOF
        );

        $this->assertEquals(['/index'], $parser->getArgs('route'));
    }


}

