<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/26
 * Time: 上午11:36
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Annotation;

/**
 * Class Annotation
 *
 * @package FastD\Annotation
 */
class Annotation extends Annotator
{
    /**
     * @var Annotation[]
     */
    protected $methods;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Annotation
     */
    protected $parent;

    /**
     * Annotation constructor.
     *
     * @param $class
     */
    public function __construct($class)
    {
        if (null !== $class) {

            $annotation = clone $this;

            $reflection = new \ReflectionClass($class);

            $params = $this->parse($reflection->getDocComment());

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $this->parse($method->getDocComment());
            }

            unset($reflection, $annotation);
        }
    }

    /**
     * @return string
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param Annotation[] $methods
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Annotation
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Annotation $parent
     * @return $this
     */
    public function setParent(Annotation $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return $this
     */
    public function __clone()
    {
        $this->methods = null;
        $this->parameters = null;
        $this->name = null;
        $this->parent = null;
        return $this;
    }
}