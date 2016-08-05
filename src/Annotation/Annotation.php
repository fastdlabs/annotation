<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation;
use FastD\Annotation\Exceptions\InvalidFunctionException;

/**
 * Class Annotation
 *
 * @package FastD\Annotation
 */
class Annotation
{
    /**
     * @var array
     */
    protected $parentAnnotations = [];

    /**
     * @var array
     */
    protected $classAnnotations = [];

    /**
     * @var array
     */
    protected $methodAnnotations = [];

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $functions = [];

    /**
     * @var array
     */
    protected $directives = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * Annotation constructor.
     * @param Parser $parser
     * @param array $directives
     */
    public function __construct(Parser $parser, array $directives = [])
    {
        $this->directives = $directives;

        $classAnnotations = $parser->getClassAnnotations();

        $this->methodAnnotations = $parser->getMethodAnnotations();

        $this->parentAnnotations = $classAnnotations; array_pop($this->parentAnnotations);

        $this->position = count($this->parentAnnotations);

        foreach ($classAnnotations as $classAnnotation) {
            $this->classAnnotations['functions'] = $this->mergeDirective($this->classAnnotations['functions'] ?? [], $classAnnotation);
            $this->mergeVariables($classAnnotation);
        }

        foreach ($this->methodAnnotations as $key => $methodAnnotation) {
            $this->methodAnnotations[$key]['functions'] = $this->mergeDirective($this->classAnnotations['functions'], $methodAnnotation);
        }

        $this->variables = $this->classAnnotations['variables'] ?? [];
        $this->functions = $this->classAnnotations['functions'] ?? [];
    }

    /**
     * @param $parent
     */
    protected function mergeVariables($parent)
    {
        if (isset($parent['variables'])) {
            foreach ($parent['variables'] as $name => $variable) {
                $this->classAnnotations['variables'][$name] = $variable;
            }
        }
    }

    public function getMethodAnnotations()
    {
        return $this->methodAnnotations;
    }
    /**
     * @param $directives
     * @param $parent
     * @return array
     */
    protected function mergeDirective($directives, $parent)
    {
        $functions = $directives;

        if (isset($parent['functions'])) {
            foreach ($parent['functions'] as $name => $parameters) {
                if (isset($this->directives[$name]) && is_callable($this->directives[$name])) {
                    $previous = $functions[$name] ?? [];
                    foreach ($parameters as $index => $value) {
                        $parameters[$index] = $this->directives[$name]($previous[$index] ?? '', $index, $value);
                    }
                }
                $functions[$name] = $parameters;
            }
        }

        return $functions;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function get($name)
    {
        return $this->variables[$name] ?? false;
    }

    /**
     * @param $name
     * @param callable|null $callable
     * @return bool|mixed
     * @throws InvalidFunctionException
     */
    public function getFunction($name, callable $callable = null)
    {
        if (!isset($this->functions[$name])) {
            return false;
        }

        $parameters = $this->functions[$name];

        if (is_callable($callable)) {
            return call_user_func_array($callable, $parameters);
        }

        if (!function_exists($name)) {
            throw new InvalidFunctionException($name);
        }

        return call_user_func_array($name, $parameters);
    }

    /**
     * @param $method
     * @return $this
     */
    public function getMethod($method)
    {
        $this->variables = $this->methodAnnotations[$method]['variables'] ?? [];
        $this->functions = $this->methodAnnotations[$method]['functions'] ?? [];

        return $this;
    }

    /**
     * @return $this
     */
    public function parent()
    {
        $this->position--;

        if (isset($this->parentAnnotations[$this->position])) {
            $this->variables = $this->parentAnnotations[$this->position]['variables'] ?? [];
            $this->functions = $this->parentAnnotations[$this->position]['functions'] ?? [];
        }

        return $this;
    }
}