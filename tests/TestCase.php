<?php

declare(strict_types=1);

namespace Juaniquillo\FluxBackendComponents\Tests;

use Juaniquillo\BackendComponents\BackendComponentsServiceProvider;
use Juaniquillo\FluxBackendComponents\FluxBackendComponentsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BackendComponentsServiceProvider::class,
            FluxBackendComponentsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['view']->addNamespace('flux', __DIR__.'/fixtures/views');
    }
}
