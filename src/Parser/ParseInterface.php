<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation\Parser;

interface ParseInterface
{
    /**
     * @param $docComment
     * @return array
     */
    public function parse($docComment);
}