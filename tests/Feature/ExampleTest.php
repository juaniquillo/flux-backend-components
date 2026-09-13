<?php

declare(strict_types=1);

use Juaniquillo\FluxBackendComponents\FluxBackendComponentsServiceProvider;

it('registers the service provider', function () {
    expect(app()->getProviders(FluxBackendComponentsServiceProvider::class))
        ->not->toBeEmpty();
});
