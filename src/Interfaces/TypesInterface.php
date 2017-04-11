<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Annotation\Interfaces;

use FastD\Annotation\Interfaces\ParseInterface;

/**
 * Interface TypesInterface
 * @package FastD\Annotation\Types
 */
interface TypesInterface extends ParseInterface
{
    /**
     * @return string
     */
    public function syntax();
}
