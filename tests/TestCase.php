<?php

declare(strict_types=1);

namespace Juaniquillo\FluxBackendComponents\Tests;

use Flux\FluxServiceProvider;
use Juaniquillo\BackendComponents\BackendComponentsServiceProvider;
use Juaniquillo\FluxBackendComponents\FluxBackendComponentsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FluxServiceProvider::class,
            BackendComponentsServiceProvider::class,
            FluxBackendComponentsServiceProvider::class,
        ];
    }
}
