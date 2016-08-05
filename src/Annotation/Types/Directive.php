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
class Directive implements TypesInterface
{
    /**
     * @return string
     */
    public function syntax()
    {
        return '/\@(\w+)\((.*?)\)/';
    }

    /**
     * @param $docComment
     * @return array
     */
    public function parse($docComment)
    {
        $pattern = $this->syntax();

        $params = [];

        if (preg_match_all($pattern, $docComment, $match)) {
            if (!isset($match[1])) {
                return [];
            }

            foreach ($match[1] as $key => $value) {
                $params[$value] = array_map(function ($v) {
                    return trim($v);
                }, explode(',', $match[2][$key]));
            }
        }

        return $params;
    }
}
