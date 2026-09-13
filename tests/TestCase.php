<?php

declare(strict_types=1);

namespace Juaniquillo\FluxBackendComponents\Tests;

use Juaniquillo\FluxBackendComponents\FluxBackendComponentsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FluxBackendComponentsServiceProvider::class,
        ];
    }
}
