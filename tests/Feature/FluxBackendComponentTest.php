<?php

declare(strict_types=1);

use Juaniquillo\BackendComponents\Contracts\ContentsComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

it('resolves a string component name', function () {
    $component = new FluxBackendComponent('card');

    expect($component->getName())->toBe('card');
});

it('resolves a component name from the enum', function () {
    $component = new FluxBackendComponent(FluxComponentEnum::CARD);

    expect($component->getName())->toBe('card');
});

it('uses the flux view namespace for the component path', function () {
    $component = new FluxBackendComponent(FluxComponentEnum::CARD);

    expect($component->getContext())->toBe('flux::')
        ->and($component->getComponentPath())->toBe('flux::.card');
});

it('accepts attributes', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setAttribute('id', 'save-btn');

    expect($component->getAttribute('id'))->toBe('save-btn')
        ->and($component->getAttributes())->toBe(['id' => 'save-btn']);
});

it('accepts content', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContent('Click me');

    expect($component->processContent())->toBeInstanceOf(ContentsComponent::class)
        ->and($component->processContent()->toArray())->toBe(['Click me']);
});

it('merges compiled themes into the attribute bag', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setTheme('color', 'success');

    expect($component->getTheme('color'))->toBe('success')
        ->and($component->compileTheme())->toBe('text-teal-900')
        ->and($component->getAttributeBag()->getAttributes())->toHaveKey('class', 'text-teal-900');
});

it('serializes the component to an array', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::CARD))
        ->setAttribute('id', 'my-card')
        ->setContent('Hello');

    $array = $component->toArray();

    expect($array)
        ->toHaveKey('name', 'card')
        ->toHaveKey('attributes', ['id' => 'my-card'])
        ->toHaveKey('content', ['Hello'])
        ->toHaveKey('path', 'flux::.card')
        ->toHaveKey('theme.manager', DefaultThemeManager::class);
});

it('renders the button component to html', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContent('Click me')
        ->setAttribute('id', 'save-btn')
        ->setTheme('color', 'success');

    $this->blade('{{ $component }}', ['component' => $component])
        ->assertSee('<button', false)
        ->assertSee('Click me')
        ->assertSee('id="save-btn"', false)
        ->assertSee('text-teal-900');
});
