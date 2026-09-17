<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use Flux\FluxServiceProvider;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxIconEnum;

function fluxIconDirectory(): string
{
    $packagePath = InstalledVersions::isInstalled('livewire/flux')
        ? InstalledVersions::getInstallPath('livewire/flux')
        : dirname((new ReflectionClass(FluxServiceProvider::class))->getFileName(), 2);

    return $packagePath.'/stubs/resources/views/flux/icon';
}

it('stays in sync with the icon components bundled by flux', function () {
    $bundledIcons = collect(glob(fluxIconDirectory().'/*.blade.php') ?: [])
        ->map(fn (string $path): string => 'icon.'.basename($path, '.blade.php'))
        ->sort()
        ->values();

    $enumIcons = collect(FluxIconEnum::cases())
        ->map(fn (FluxIconEnum $icon): string => $icon->value)
        ->sort()
        ->values();

    expect($enumIcons->all())->toBe($bundledIcons->all());
});

it('defines a unique set of icon component names', function () {
    $values = collect(FluxIconEnum::cases())->pluck('value');

    expect($values)->not->toBeEmpty()
        ->and($values->unique())->toHaveCount($values->count())
        ->and($values->every(fn (string $value): bool => preg_match('/^icon\.[a-z0-9]+(?:-[a-z0-9]+)*$/', $value) === 1))->toBeTrue();
});

it('derives the case name from the icon slug', function () {
    foreach (FluxIconEnum::cases() as $icon) {
        $expected = strtoupper(str_replace('-', '_', str_replace('icon.', '', $icon->value)));

        expect($icon->name)->toBe($expected);
    }
});

it('renders bundled icons as standalone components', function (FluxIconEnum $icon) {
    $html = (new FluxBackendComponent($icon))->toHtml();

    expect($html)->toContain('data-flux-icon');
})->with([
    FluxIconEnum::BOLT,
    FluxIconEnum::ARROW_DOWN_ON_SQUARE_STACK,
    FluxIconEnum::LOADING,
]);

it('renders icon variants', function () {
    $html = (new FluxBackendComponent(FluxIconEnum::BOLT))
        ->setAttribute('variant', 'solid')
        ->toHtml();

    expect($html)->toContain('data-flux-icon')
        ->toContain('fill="currentColor"');
});
