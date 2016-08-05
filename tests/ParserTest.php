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
use PHPUnit_Framework_TestCase;
use Tests\AnnotationsClasses\IndexController;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseSyntax()
    {
        $parser = new Parser(IndexController::class);
    }
}

