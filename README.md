# Lazy-Properties

- [Lazy-Properties](#lazy-properties)
  - [Installation](#installation)
  - [Usage](#usage)
    - [Declaring Lazy Properties](#declaring-lazy-properties)
      - [Common Initializer Method](#common-initializer-method)
    - [Subclasses's Readonly, Lazy-initialized Properties](#subclassess-readonly-lazy-initialized-properties)

***

## Installation

Install *lazy-props* via Composer:

```bash
composer require ali-eltaweel/lazy-props
```

## Usage

### Declaring Lazy Properties

```php
use Lang\{ Annotations\LazyInitialized, LazyProperties };

class Database {

  use LazyProperties;

  #[LazyInitialized('createConnection')]
  public $connection;

  function createConnection() {
        
    return new stdClass();
  }
}
```

Upon instantiation of the `Database` class, the `connection` property will be unset, so that calls to `$db->connection` are directed to the `__get` magic method provided by the `LazyProperties` trait. This method will then call the `createConnection` method on the first call to `$db->connection`, and set the `connection` property to the return value of that method. Subsequent calls to `$db->connection` will return the already initialized value.

#### Common Initializer Method

If your class declares more than one lazy property, you can use a common initializer method for all of them:

```php
class X {
  
  use LazyProperties;

  #[LazyInitialized('createArgument')]
  public int $x;
  
  #[LazyInitialized('createArgument')]
  public int $y;

  private function createArgument(string $name): int {
      
    echo "Initializing {$name}...\n";
    
    return match ($name) {
      'x' => 42,
      'y' => 84,
    };
  }
}
```

You can also you the `InitializerMethod` annotation on your class to save yourself some typing:

```php
#[InitializerMethod('createArgument')]
class X {

  #[LazyInitialized()]
  public int $x;
  
  #[LazyInitialized()]
  public int $y;
}
```

***

### Subclasses's Readonly, Lazy-initialized Properties

Take a look at the following example:

```php
class X {

  use LazyProperties;

  #[LazyInitialized('getX')]
  public int $x;

  private function getX() {
      
    return 42;
  }
}

class Y extends X {

  #[LazyInitialized('getY')]
  public int $y;

  // you can't make this initializer private, because it is called from the parent class.
  protected function getY() {
    
    echo "Initializing y...\n";
    return 84;
  }
}
```

It looks fine, and it is...

```php
$y = new Y();

var_dump($y->y);
var_dump($y->y);
// "Initializing y..." is printed only once
```

But, when you change the `y` property to be `readonly`:

```php
class Y extends X {

  #[LazyInitialized('getY')]
  public readonly int $y;
}
```

... you will get an error by just instantiating the `Y` class:

```
Cannot unset readonly property Y::$y from scope X 
```

To fix this; each class has to unset (uninitialize) its own lazy properties, to do so, use the `LazyPropertiesExtension` trait in subclasses (of all generations) and annotate them with the `ExtendedLazyProperties` annotation giving the names of the extended lazy properties:

```php
use Lang\{ Annotations\ExtendedLazyProperties, LazyPropertiesExtension };

#[ExtendedLazyProperties('y')]
class Y extends X {

  use LazyPropertiesExtension;

  #[LazyInitialized('getY')]
  public readonly int $y;
}
```

```php
#[ExtendedLazyProperties('z')]
class Z extends Y {

  use LazyPropertiesExtension;

  #[LazyInitialized('getZ')]
  public readonly int $z;
}
```
