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
 * Class Types
 * @package FastD\Annotation\Types
 */
abstract class Types implements TypesInterface
{
    /**
     * @param $docComment
     * @return array
     */
    public function parse($docComment)
    {
        $pattern = $this->syntax();

        $params = [];

        if (preg_match_all($pattern, str_replace(array("\r\n", "\n", '*'), '', $docComment), $match)) {
            foreach ($match['name'] as $key => $name) {
                $params[$name][$key] = $match['params'][$key];
            }
            unset($match);

            foreach ($params as $name => $param) {
                $param = preg_split('/(?<=\"|\]|\}),\s*(?=\w)/', str_replace('\\', '\\\\', implode(',', $param)));
                $parameters = [];
                foreach ($param as $key => $value) {
                    if (false !== strpos($value, '=')) {
                        list($key, $value) = explode('=', $value);
                        if (false !== ($json = json_decode($value, true))) {
                            $value = $json;
                            unset($json);
                        }
                    } else {
                        $value = trim($value, '"');
                    }

                    $parameters[$key] = $value;
                }
                $params[$name] = $parameters;
                unset($parameters);
            }
        }

        return $params;
    }
}
