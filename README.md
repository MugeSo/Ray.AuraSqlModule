# Ray.AuraSqlModule
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/?branch=develop)
[![Build Status](https://travis-ci.org/Ray-Di/Ray.AuraSqlModule.svg?branch=master)](https://travis-ci.org/Ray-Di/Ray.AuraSqlModule)

[Aura.Sql](https://github.com/auraphp/Aura.Sql) Module for [Ray.Di](https://github.com/koriym/Ray.Di)

## Installation

### Composer install

    $ composer require ray/aura-sql-module
 
### Module install

Single DB
```php
use Ray\Di\AbstractModule;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\Annotation\AuraSqlConfig;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new AuraSqlModule('mysql:host=localhost;dbname=test', 'username', 'password');
        
        // or
        // $this->install(new AuraSqlModule);
        // $this->bind()->annotatedWith(AuraSqlConfig::class)->toInstance([$dsn ,$user ,$password]);
    }
}
```
### DI trait

 * [AuraSqlInject](https://github.com/Ray-DI/Ray.AuraSqlModule/blob/master/src/AuraSqlInject.php) for `Aura\Sql\ExtendedPdoInterface` interface
 

#### Master / Slave database

Frequently, high-traffic PHP applications use multiple database servers, generally one for writes, and one or more for reads.
A `AuraSqlLocatorModule` can be configured to locate proper database by method basis.

```php
use Ray\Di\AbstractModule;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\Annotation\AuraSqlConfig;
use Aura\Sql\ConnectionLocator;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $locator = new ConnectionLocator;
        $locator->setWrite('master', new Connection('mysql:host=localhost;dbname=master', 'username', 'password'));
        $locator->setRead('slave1', new Connection('mysql:host=localhost;dbname=slave1', 'username', 'password'));
        $locator->setRead('slave2', new Connection('mysql:host=localhost;dbname=slave2', 'username', 'password'));
        $this->install(new new AuraSqlLocatorModule($locator, ['read'], ['create', 'update', 'delete']);
    }
}

```

When `@ReadOnlyConnection` annotated method is called, Read-only `$pdo`(slave database) is injected to the `$pdo` property. Or `@WriteConnection` for master database connection.

```php

use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;  // <-important
use Ray\AuraSqlModule\Annotation\WriteConnection;     // <-important

class User
{
    public $pdo;

    /**
     * @ReadOnlyConnection
     */
    public function read()
    {
         $this->$pdo: // slave db
    }

    /**
     * @WriteConnection
     */
    public function write()
    {
         $this->$pdo: // master db
    }
}
```

In `@AuraSql` annotated class, Master / slave database is automatically switched in every method call by registered method name.

```php

use Ray\AuraSqlModule\Annotation\AuraSql; // <-important

/**
 * @AuraSql
 */
class User
{
    public $pdo;

    public function read()
    {
         $this->$pdo: // slave db
    }

    public function write()
    {
         $this->$pdo: // master db
    }
}
```

### Demo

    $ php docs/demo/run.php
    // It works!

### Requirements

 * PHP 5.4+
 * hhvm
 
