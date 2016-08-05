<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation\Types;

/**
 * Class Directive
 * @package FastD\Annotation\Types
 */
class Directive extends Types
{
    /**
     * @return string
     */
    public function syntax()
    {
        return '/\@(?P<name>\w+)\((?P<params>.*?)\)/';
    }
}
