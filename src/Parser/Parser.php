<?php

namespace FastD\Annotation\Parser;

use FastD\Annotation\Exceptions\UndefinedAnnotationFunctionException;
use FastD\Annotation\Exceptions\UndefinedAnnotationVariableException;
use FastD\Annotation\Types\Functions;
use FastD\Annotation\Types\Variable;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
class Parser implements ParseInterface
{
    protected $annotations = [];

    /**
     * @var array
     */
    protected $types = [
        'functions' => Functions::class,
        'variables' => Variable::class,
    ];

    /**
     * @param $docComment
     * @return array
     */
    public function parse($docComment)
    {
        if (empty($docComment)) {
            return [];
        }

        $annotations = [];

        foreach ($this->types as $name => $type) {
            $annotations[$name] = (new $type)->parse($docComment);
            $this->annotations[$name] = $annotations[$name];
        }

        return $annotations;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        if (!isset($this->annotations['variables'][$name])) {
            throw new UndefinedAnnotationVariableException($name);
        }

        return $this->annotations['variables'][$name];
    }

    public function getArgs($name)
    {
        if (!isset($this->annotations['functions'][$name])) {
            throw new UndefinedAnnotationFunctionException($name);
        }

        return $this->annotations['functions'][$name];
    }

    /**
     * @param $definition
     * @return mixed
     */
    public function execute(array $definition = [])
    {
        if (!isset($this->annotations['functions'])) {
            return [];
        }

        foreach ($this->annotations['functions'] as $name => $arguments) {
            if (isset($definition[$name])) {
                call_user_func_array($definition[$name], $arguments);
                continue;
            }

            if (function_exists($name)) {
                call_user_func_array($name, $arguments);
                continue;
            }

            throw new UndefinedAnnotationFunctionException($name);
        }
    }
}