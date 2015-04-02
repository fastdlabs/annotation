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
        if (!class_exists('\\Demo')) {
            include __DIR__ . '/Demo.php';
        }
    }
    
    public function testAnnotationOne()
    {
        $annotationExtractor = ClassParser::getExtractor('\Demo');

        $annotation = $annotationExtractor->getMethodAnnotation('demoAction');

        $parameters = $annotationExtractor->getParameters($annotation, 'Route');

        $this->assertEquals(
            array(
                '/{name}',
                'name' => 'abc',
                'defaults' => array(
                    'name' => 'jan'
                ),
                'method' => array(
                    'POST'
                )
            ),
            $parameters
        );

        $annotation = $annotationExtractor->getMethodAnnotation('demoAction2');

        /**
         * @Route("/{name}", name="abc", defaults={"name": "jan"}, format=["json", "php", "xml"])
         * @Route(requirements={"name":"\w+"}, method=["POST", "GET"])
         */
        $parameters = $annotationExtractor->getParameters($annotation, 'Route');

        $this->assertEquals(
            array(
                '/{name}',
                'name' => 'abc',
                'defaults' => array(
                        'name' => 'jan'
                    ),
                'method' => array(
                    'POST'
                    ),
                'format' => array(
                    'json', 'php', 'xml',
                ),
                'requirements' => array(
                    'name' => '\w+'
                ),
                'method' => array(
                    'POST', 'GET'
                )
                ),

            $parameters
        );
    }
}