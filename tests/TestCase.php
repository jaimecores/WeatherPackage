<?php

namespace JaimeCores\WeatherPackage\Tests;

use JaimeCores\WeatherPackage\WeatherPackageServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    
    protected function getPackageProviders($app)
    {
        return [
            WeatherPackageServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'mysql',
            'url' => 'mysql://root:root@localhost:3306/laravel',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}