<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/1
 * Time: 下午11:06
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace FastD\Annotation;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
abstract class Annotator
{
    /**
     * @var string
     */
    protected $separator = '@';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * @param string $separator
     * @return $this
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getParameters($name = null)
    {
        if (null === $name) {
            return $this->parameters;
        }

        return isset($this->parameters[$name]) ? $this->parameters[$name] : false;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param $annotation
     * @return array
     */
    public function parse($annotation)
    {
        $pattern = sprintf('/\%s(?P<name>\w+)\((?P<params>.*?)\)/', $this->getSeparator());

        $params = [];

        if (preg_match_all($pattern, str_replace(array("\r\n", "\n", '*'), '', $annotation), $match)) {
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