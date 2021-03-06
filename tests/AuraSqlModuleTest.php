<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Injector;

class AuraSqlModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $instance = (new Injector(new AuraSqlModule('sqlite::memory:'), $_ENV['TMP_DIR']))->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }
}
