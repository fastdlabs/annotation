<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/1
 * Time: 下午11:13
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Annotation\Tests;

use Dobee\Annotation\ClassParser;

class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include __DIR__ . '/Demo.php';
    }

    public function testAnnotationOne()
    {
        echo ClassParser::getExtractor('\Demo')->extractClassAnnotation('demoAction');
    }
}