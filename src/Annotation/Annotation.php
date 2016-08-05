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
     * @var int
     */
    protected $position = 0;

    /**
     * Annotation constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $classAnnotations = $parser->getClassAnnotations();

        $this->methodAnnotations = $parser->getMethodAnnotations();

        $this->parentAnnotations = $classAnnotations; array_pop($this->parentAnnotations);

        $this->position = count($this->parentAnnotations);

        foreach ($classAnnotations as $classAnnotation) {
            $this->mergeDirective($classAnnotation);
            $this->mergeVariables($classAnnotation);
        }

        $this->variables = $this->classAnnotations['variables'] ?? [];
        $this->functions = $this->classAnnotations['directives'] ?? [];
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

    /**
     * @param $parent
     */
    protected function mergeDirective($parent)
    {
        if (isset($parent['directives'])) {
            foreach ($parent['directives'] as $name => $parameters) {
                $this->classAnnotations['directives'][$name] = $parameters;
            }
        }
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
     * @return bool|mixed
     */
    public function getFunction($name)
    {
        return $this->functions[$name] ?? false;
    }

    /**
     * @param $method
     * @return $this
     */
    public function getMethod($method)
    {
        $this->variables = $this->methodAnnotations[$method]['variables'] ?? [];
        $this->functions = $this->methodAnnotations[$method]['directives'] ?? [];

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
            $this->functions = $this->parentAnnotations[$this->position]['directives'] ?? [];
        }

        return $this;
    }
}