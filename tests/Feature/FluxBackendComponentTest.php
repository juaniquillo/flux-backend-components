<?php

declare(strict_types=1);

use Juaniquillo\BackendComponents\Contracts\ContentsComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

it('resolves a component name from the enum', function () {
    $component = new FluxBackendComponent(FluxComponentEnum::CARD);

    expect($component->getName())->toBe('card');
});

it('uses the flux view namespace for the component path', function () {
    $component = new FluxBackendComponent(FluxComponentEnum::CARD);

    expect($component->getContext())->toBe('flux::')
        ->and($component->getComponentPath())->toBe('flux::.card');
});

it('uses the flux namespace for dotted enum values', function () {
    $component = new FluxBackendComponent(FluxComponentEnum::OPTION);

    expect($component->getComponentPath())->toBe('flux::.select.option');
});

it('drops the flux namespace for local resolution', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))->useLocal();

    expect($component->getNamespace())->toBeNull()
        ->and($component->getComponentPath())->toBe('button');
});

it('appends a custom path to the component path', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::CARD))->setPath('pro');

    expect($component->getPathOnly())->toBe('pro')
        ->and($component->getComponentPath())->toBe('flux::pro.card');
});

it('keeps the flux context regardless of the namespace', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::CARD))->setNamespace('other');

    expect($component->getContext())->toBe('flux::')
        ->and($component->getComponentPath())->toBe('flux::.card');
});

it('accepts attributes', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setAttribute('id', 'save-btn');

    expect($component->getAttribute('id'))->toBe('save-btn')
        ->and($component->getAttributes())->toBe(['id' => 'save-btn']);
});

it('accepts multiple attributes at once', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setAttributes(['id' => 'save-btn', 'class' => 'primary']);

    expect($component->getAttributes())->toBe(['id' => 'save-btn', 'class' => 'primary']);
});

it('returns null for a missing attribute', function () {
    expect((new FluxBackendComponent(FluxComponentEnum::BUTTON))->getAttribute('missing'))->toBeNull();
});

it('accepts content', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContent('Click me');

    expect($component->processContent())->toBeInstanceOf(ContentsComponent::class)
        ->and($component->processContent()->toArray())->toBe(['Click me']);
});

it('accepts keyed content', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContent('Hello', 'greeting');

    expect($component->getContent('greeting'))->toBe('Hello')
        ->and($component->processContent()->toArray())->toBe(['greeting' => 'Hello']);
});

it('accepts a batch of content', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContents(['Hello', 'World']);

    expect($component->getContents())->toBe(['Hello', 'World'])
        ->and($component->processContent()->toArray())->toBe(['Hello', 'World']);
});

it('accepts a keyed batch of content with overwrite', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContents(['first' => 'Hello', 'second' => 'World'], overwrite: true);

    expect($component->getContents())->toBe(['first' => 'Hello', 'second' => 'World']);
});

it('prepends content in order', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContent('World')
        ->prependContent('Hello');

    expect($component->getContents())->toBe(['Hello', 'World']);
});

it('removes content by key', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContents(['first' => 'Hello', 'second' => 'World'], overwrite: true)
        ->unsetContent('first');

    expect($component->getContents())->toBe(['second' => 'World']);
});

it('clears all content', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContent('Hello')
        ->setContent('World')
        ->unsetContent();

    expect($component->getContents())->toBe([]);
});

it('merges compiled themes into the attribute bag', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setTheme('color', 'success');

    expect($component->getTheme('color'))->toBe('success')
        ->and($component->compileTheme())->toBe('text-teal-900')
        ->and($component->getAttributeBag()->getAttributes())->toHaveKey('class', 'text-teal-900');
});

it('accumulates theme variants for the same theme name', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setTheme('color', 'success')
        ->setTheme('color', 'error');

    expect($component->getTheme('color'))->toBe(['success', 'error']);
});

it('overwrites a theme variant when requested', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setTheme('color', 'success')
        ->setTheme('color', 'danger', overwrite: true);

    expect($component->getTheme('color'))->toBe('danger');
});

it('builds themes in batch', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setThemes(['color' => 'success', 'action' => 'link']);

    expect($component->getThemes())->toBe(['color' => 'success', 'action' => 'link']);
});

it('compiles to null when no themes are set', function () {
    expect((new FluxBackendComponent(FluxComponentEnum::BUTTON))->compileTheme())->toBeNull();
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
        ->toHaveKey('theme.manager', DefaultThemeManager::class)
        ->toHaveKey('theme.themes', $component->getThemes())
        ->toHaveKey('theme.path', $component->getThemeManager()->getDefaultPath())
        ->toHaveKey('theme.realPath', $component->getThemeManager()->getThemePath());
});

it('serializes to json when cast to string', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::CARD))
        ->setContent('Hello');

    expect((string) $component)->toBe(\json_encode($component->toArray()));
});

it('renders the button component to html', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setContent('Click me')
        ->setAttribute('id', 'save-btn')
        ->setTheme('color', 'success');

    $this->blade('{{ $component }}', ['component' => $component])
        ->assertSee('<button', false)
        ->assertSee('data-flux-button', false)
        ->assertSee('Click me')
        ->assertSee('id="save-btn"', false)
        ->assertSee('text-teal-900');
});

it('renders any flux component to html', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::CARD))
        ->setContent('Hello');

    $this->blade('{{ $component }}', ['component' => $component])
        ->assertSee('Hello')
        ->assertDontSee('data-flux-button', false);
});
