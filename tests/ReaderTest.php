<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Tests;

use FastD\Annotation\Reader;
use PHPUnit_Framework_TestCase;
use Tests\AnnotationsClasses\IndexController;

class ReaderTest extends PHPUnit_Framework_TestCase
{
    public function testAnnotationReader()
    {
        $reader = new Reader();

        $annotation = $reader->getAnnotations(IndexController::class);

        $this->assertEquals('foo', $annotation->get('name'));
    }
}

