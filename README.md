# Annotation

简单的 PHP 注解解析类

## 要求

* PHP 7+

## Composer

```json
{
    "fastd/annotation": "2.0.x-dev"
}
```

## 使用

```php
use FastD\Annotation\Tests\Demo;
use FastD\Annotation\Annotation;

$annotation = new Annotation(Demo::class);

print_r($annotation->getMethods()[0]->getParameters('Route'));
```

查看 `Demo` 内部方法注释，目前只支持 json 字符，赋值解析。类似 Symfony 注释写法类似。

上述 demo 会产生: 

```php
Array
(
    [0] => /{name}
    [name] => abc
    [defaults] => Array
        (
            [name] => jan
        )

    [method] => Array
        (
            [0] => POST
        )

)
```

对应会解析对应的参数。

通过 `FastD\Annotation\Annotation::getParameters($name = null)` 进行获取解析内容。

**相同注释会进行自动合并。**

注释组件对象会自动解析 `public` 方法进行解析，其中可以通过 `FastD\Annotation\Annotation::__construct($class, $prefix = null, $suffix = null)` 第二、第三个参数进行对方法名前缀、后缀得控制，过滤得出指定得方法。

```php
$annotation = new Annotation(Demo::class, null, 'Action');
```

获取 `Demo::class` 对象 `Action` 结尾得所有得 `public` 方法.

## Testing

```php
phpunit
```

## License MIT


