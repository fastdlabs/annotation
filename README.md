# Annotation

![Building](https://api.travis-ci.org/JanHuang/annotation.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/fastd/annotation/v/stable)](https://packagist.org/packages/fastd/annotation) [![Total Downloads](https://poser.pugx.org/fastd/annotation/downloads)](https://packagist.org/packages/fastd/annotation) [![Latest Unstable Version](https://poser.pugx.org/fastd/annotation/v/unstable)](https://packagist.org/packages/fastd/annotation) [![License](https://poser.pugx.org/fastd/annotation/license)](https://packagist.org/packages/fastd/annotation)

简单的 PHP 类注释解析类

## 要求

* PHP 5.6+

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

## Testing

```php
phpunit
```

## License MIT


