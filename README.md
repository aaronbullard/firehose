# Firehose Hydrator
[![Maintainability](https://api.codeclimate.com/v1/badges/5b94659aae96845f7024/maintainability)](https://codeclimate.com/github/aaronbullard/firehose/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5b94659aae96845f7024/test_coverage)](https://codeclimate.com/github/aaronbullard/firehose/test_coverage)

PHP Reflection Class helper to Instantiate, Mutate, and Extract data directly within a class.

## Installation

### Library

```bash
git clone git@github.com:aaronbullard/firehose.git
```

### Composer

[Install PHP Composer](https://getcomposer.org/doc/00-intro.md)

```bash
composer require aaronbullard/firehose
```

### Testing

```bash
composer test
```

## Usage

Firehose/Hydrator uses PHP reflection to bypass protected and private attribute constraints.

This can be useful when trying to instantiate objects from a database query using a mapper pattern.  This method also allows you to bypass any validation constraints within the constructor.

When trying to persist the object, anemic getters are unnecessary.  Instance properties can be exctracted as key/value pairs for database persistence.

## Examples

Given Foo

```php
class Foo
{
    private $bar;
    protected $baz;
    private $qux;

    public function __construct($bar, $baz = null, $qux = null)
    {
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
    }

    public function bar(){ return $this->bar; }
    public function baz(){ return $this->baz; }
}
```

Create a new instance
```php
use Firehose\Hydrator;

$foo = Hydrator::newInstance(Foo::class, [
    'bar' => 'bar',
    'baz' => 'baz'
]);

$this->assertInstanceOf(Foo::class, $foo);
$this->assertEquals('bar', $foo->bar());
$this->assertEquals('baz', $foo->baz());
```

Mutating a live instance
```php
$foo = new Foo('bar', 'baz');

Hydrator::mutate($foo, [
    'bar' => 'newBar'
]);

$this->assertEquals('newBar', $foo->bar());
```

Extracting data
```php
$foo = new Foo('bar', 'baz', 'qux');

$data = Hydrator::extract($foo, ['bar', 'baz', 'qux']);

$this->assertEquals('bar', $data['bar']);
$this->assertEquals('baz', $data['baz']);
$this->assertEquals('qux', $data['qux']);
```

For more examples, see the tests: `tests\HydratorTest.php`