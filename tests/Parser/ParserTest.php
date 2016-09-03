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
}

