<?php

declare(strict_types=1);

use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use Juaniquillo\FluxBackendComponents\Factories\FluxComponentFactory;
use Juaniquillo\FluxBackendComponents\Factories\FluxLocalThemeComponentFactory;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

it('builds a component from the enum', function () {
    $component = FluxComponentFactory::make(FluxComponentEnum::BUTTON);

    expect($component)->toBeInstanceOf(FluxBackendComponent::class)
        ->and($component->getName())->toBe('button');
});

it('resolves local themes with the local theme factory', function () {
    $component = FluxLocalThemeComponentFactory::make(FluxComponentEnum::BUTTON);

    expect($component)->toBeInstanceOf(FluxBackendComponent::class)
        ->and($component->getThemeManager())->toBeInstanceOf(LocalThemeManager::class)
        ->and($component->getName())->toBe('button');
});

it('compiles themes from the app local theme path', function () {
    $component = FluxLocalThemeComponentFactory::make(FluxComponentEnum::BUTTON)
        ->setTheme('color', 'success');

    $component->getThemeManager()->setDefaultPath(__DIR__.'/../../fixtures/views/_themes/tailwind');

    expect($component->compileTheme())->toBe('text-violet-900');
});

it('renders a factory component to html', function () {
    $component = FluxComponentFactory::make(FluxComponentEnum::BUTTON)
        ->setContent('Click me');

    $this->blade('{{ $component }}', ['component' => $component])
        ->assertSee('<button', false)
        ->assertSee('data-flux-button', false)
        ->assertSee('Click me');
});
