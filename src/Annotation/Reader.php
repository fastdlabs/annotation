<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation;

/**
 * Class Reader
 * @package FastD\Annotation
 */
class Reader
{
    /**
     * @var Annotation[]
     */
    protected $annotations = [];

    /**
     * @var array
     */
    protected $directives = [];

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setDirective($name, $value)
    {
        $this->directives[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasDirective($name)
    {
        return isset($this->directives[$name]);
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getDirective($name)
    {
        if (!$this->hasDirective($name)) {
            return false;
        }

        return $this->directives[$name];
    }

    /**
     * @param $class
     * @return Annotation
     */
    public function getAnnotations($class)
    {
        if (!isset($this->annotations[$class])) {
            $parser = new Parser($class);

            $annotation = new Annotation();

            foreach ($parser->getClassAnnotations() as $class => $classAnnotation) {
                if (isset($classAnnotation['variables'])) {
                    foreach ($classAnnotation['variables'] as $name => $value) {
                        $annotation->set($name, $value);
                    }
                }
                if (isset($classAnnotation['directives'])) {
                    foreach ($classAnnotation['directives'] as $name => $value) {
                        $annotation->setDirective($name, $value);
                    }
                }
            }

            foreach ($parser->getMethodAnnotations() as $method => $methodAnnotation) {
                if (isset($methodAnnotation['variables'])) {
                    foreach ($methodAnnotation['variables'] as $name => $value) {
                        $annotation->set($name, $value);
                    }
                }
                if (isset($methodAnnotation['directives'])) {
                    foreach ($methodAnnotation['directives'] as $name => $value) {
                        $annotation->setDirective($name, $value);
                    }
                }
            }

            $this->annotations[$class] = $annotation;
        }

        return $this->annotations[$class];
    }
}
