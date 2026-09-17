<?php

declare(strict_types=1);

use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use Juaniquillo\FluxBackendComponents\Builders\FluxComponentBuilder;
use Juaniquillo\FluxBackendComponents\Builders\FluxLocalThemeComponentBuilder;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

it('builds a component from the enum', function () {
    $component = FluxComponentBuilder::make(FluxComponentEnum::BUTTON);

    expect($component)->toBeInstanceOf(FluxBackendComponent::class)
        ->and($component->getName())->toBe('button');
});

it('resolves local themes with the local theme builder', function () {
    $component = FluxLocalThemeComponentBuilder::make(FluxComponentEnum::BUTTON);

    expect($component)->toBeInstanceOf(FluxBackendComponent::class)
        ->and($component->getThemeManager())->toBeInstanceOf(LocalThemeManager::class)
        ->and($component->getName())->toBe('button');
});

it('renders a builder component to html', function () {
    $component = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
        ->setContent('Click me');

    $this->blade('{{ $component }}', ['component' => $component])
        ->assertSee('<button', false)
        ->assertSee('data-flux-button', false)
        ->assertSee('Click me');
});
