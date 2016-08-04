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
use FastD\Annotation\Annotation;

$annotation = new Annotation(\FastD\Annotation\Tests\Test::class);

print_r($annotation->getAnnotator('testAction')->getParameters());
```

查看 `Demo` 内部方法注释，目前只支持 json 字符，赋值解析。类似 Symfony 注释写法类似。

上述 demo 会产生: 

```php
Array
(
    [0] => /self/{name}
    [name] => test
)
```

对应会解析对应的参数。

**相同注释会进行自动合并。**

## Testing

```php
phpunit
```

## License MIT


