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
use FastD\Annotation\Types\Directive;
use FastD\Annotation\Types\Variable;
use Tests\AnnotationsClasses\IndexController;
use PHPUnit_Framework_TestCase;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseSyntax()
    {
//        $parser = new Parser(IndexController::class);
    }

    public function testVariableParseSyntax()
    {
        $parse = new Variable();

        $annotation = $parse->parse(<<<EOF
/**
 * Class IndexController
 * @package Tests\AnnotationsClassess
 *
 * @name foo
 * @json ["abc"]
 * @directive("test")
 * @Tests\AnnotationsClasses\AnnotationObject -> test()
 */
EOF
);
    }

    public function testDirectiveParseSyntax()
    {
        $parse = new Directive();

        $annotation = $parse->parse(<<<EOF
/**
 * Class IndexController
 * @package Tests\AnnotationsClassess
 *
 * @name foo
 * @json ["abc"]
 * @directive("test", "bbb")
 * @directive2("test", "bbb")
 * @Tests\AnnotationsClasses\AnnotationObject -> test()
 */
EOF
);

        print_r($annotation);
    }
}

