# Annotation

![Building](https://api.travis-ci.org/JanHuang/annotation.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/fastd/annotation/v/stable)](https://packagist.org/packages/fastd/annotation) [![Total Downloads](https://poser.pugx.org/fastd/annotation/downloads)](https://packagist.org/packages/fastd/annotation) [![Latest Unstable Version](https://poser.pugx.org/fastd/annotation/v/unstable)](https://packagist.org/packages/fastd/annotation) [![License](https://poser.pugx.org/fastd/annotation/license)](https://packagist.org/packages/fastd/annotation)

简单的 PHP 类注释解析类

## 要求

* PHP 7.0+

## Composer

```json
composer require "fastd/annotation"
```

## 使用

```php
use FastD\Annotation\Reader;
use Tests\AnnotationsClasses\IndexController;

$reader = new Reader();

/**
 * Class IndexController
 * @package Tests\AnnotationsClasses
 *
 * @name foo
 * @json ["abc"]
 * @directive("test")
 * @route("/")
 * @Tests\AnnotationsClasses\AnnotationObject -> test()
 */
$annotation = $reader->getAnnotations(IndexController::class);

$annotation->get('name'); // foo
$annotation->get('json'); // [ 'ab' ]
```

## 继承与覆盖

变量同名会覆盖　"父类"　的变量和函数。

## 指令

指令即为函数, 如上述注释, `@route` 即是一个指令, 若在注释器中没有设置指令, 则不会对其进行任何操作, 否则会按照指令进行处理。

这里一个常见的例子: 路由 与 路由继承

```php
/**

---- BaseController ----

/**
 * Class BaseController
 * @package Tests\AnnotationsClasses
 *
 * @name base
 * @json ["abc"]
 * @directive("test")
 * @route("/base")
 */
class BaseController
{

}

---- ChildController ----

/**
 * Class ChildController
 * @package Tests\AnnotationsClasses
 *
 * @name child
 * @json ["abc"]
 * @directive("/test")
 * @route("/child")
 */
class ChildController extends BaseController
{
   /**
    * @name method
    * @route("/index")
    */
   public function indexAction()
   {}
}

 */

use FastD\Annotation\Reader;
use Tests\AnnotationsClasses\ChildController;

$reader = new Reader([
    'route' => function ($previous, $index, $value) {
        return $previous . $value;
    }
]);

$annotation = $reader->getAnnotations(ChildController::class);

$routeResult = $annotation->getFunction('route'); // /bash/child

$routeResult = $annotation->getMethod('indexAction')->getFunction('route'); // /bash/child/index
```

所有 `@route` 指令都会执行 `Reader` 对象中设置的指令, 指令索引和注释指令保持一直命名。

如上述所示, 最终 `@route` 指令会将每个参数进行拼接处理, 达到路由继承的效果。

指令匿名函数接受 3 个参数, 参数由系统进行分配, 分别是: 上一个指令结果, 当前参数下表, 当前参数值

## Testing

```php
phpunit
```

## License MIT


