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

## ＃　继承与覆盖

变量同名会覆盖　"父类"　的变量，而指令，则会追加继承父类的实现．

如：　

```
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
class IndexController
{
    /**
     * @name index
     * @route("/index")
     */
    public function indexAction()
    {}
    
    /**
     * @route("/default")
     */
    public function defaultAction()
    {}
}
```

在 `indexAction` 中获取 `name` 变量，则会输出 `index`，而在　`defaultAction` 中，则会输出　`foo`，其相当于 "父类"　定义已全局基础变量，而子类负责重写覆盖或读取父类．

在指令中，则以继承方式展现．如上

`route` 指令在 "父类" 中定义 "根"，"子类"　方法中会继承 "父类"，`/index`．

## Testing

```php
phpunit
```

## License MIT


