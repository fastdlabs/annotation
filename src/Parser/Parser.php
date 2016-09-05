<?php

namespace FastD\Annotation\Parser;

use FastD\Annotation\Types\Functions;
use FastD\Annotation\Types\Variable;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class Annotator
 *
 * @package FastD\Annotation
 */
class Parser implements ParseInterface
{
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
        }

        return $annotations;
    }
}