<?php

declare(strict_types=1);

use Flux\FluxServiceProvider;
use Juaniquillo\FluxBackendComponents\FluxBackendComponentsServiceProvider;
use Livewire\LivewireServiceProvider;

it('registers and boots the flux backend components provider', function () {
    $provider = $this->app->getProvider(FluxBackendComponentsServiceProvider::class);

    expect($provider)->toBeInstanceOf(FluxBackendComponentsServiceProvider::class);

    $loadedProviders = $this->app->getLoadedProviders();

    expect($loadedProviders[FluxBackendComponentsServiceProvider::class] ?? false)->toBeTrue();
});

it('registers the providers required to render flux components', function () {
    expect($this->app->getProvider(LivewireServiceProvider::class))
        ->toBeInstanceOf(LivewireServiceProvider::class)
        ->and($this->app->getProvider(FluxServiceProvider::class))
        ->toBeInstanceOf(FluxServiceProvider::class);
});
