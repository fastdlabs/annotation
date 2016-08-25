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
 * Interface TypesInterface
 * @package FastD\Annotation\Types
 */
interface TypesInterface
{
    /**
     * @return string
     */
    public function syntax();

    /**
     * @param $docComment
     * @return array
     */
    public function parse($docComment);
}
