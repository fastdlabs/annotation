<?php

namespace FastD\Annotation;

use ReflectionClass;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
abstract class Annotator
{
    const SEPARATOR = '@';

    /**
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->reflection->getName();
    }

    abstract public function getClassName();

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
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
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->parameters);
    }

    /**
     * @param $annotation
     * @return array
     */
    protected function parse($annotation)
    {
        $pattern = sprintf('/\%s(?P<name>\w+)\((?P<params>.*?)\)/', static::SEPARATOR);

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