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
 * Class Variable
 * @package FastD\Annotation\Types
 */
class Variable implements TypesInterface
{
    /**
     * @return string
     */
    public function syntax()
    {
        return '/\@([a-zA-Z0-9]{1,})\s{1,}(.*)/';
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
                $json = json_decode($match[2][$key], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $match[2][$key] = $json;
                }
                $params[$value] = $match[2][$key];
            }
        }

        return $params;
    }
}
