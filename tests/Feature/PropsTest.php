<?php

declare(strict_types=1);

use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;
use Juaniquillo\FluxBackendComponents\Utils\FluxUITableUtil;

it('keeps scalar attributes on the conforming channel', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
        ->setAttribute('id', 'save-btn')
        ->setAttribute('tabindex', 1);

    expect($component->getAttribute('id'))->toBe('save-btn')
        ->and($component->getAttribute('tabindex'))->toBe(1)
        ->and($component->getAttributes())->toBe(['id' => 'save-btn', 'tabindex' => 1])
        ->and($component->getProps())->toBe([]);
});

it('keeps rich values out of the scalar read path', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::TABLE))
        ->setProp('bleed', true);

    expect($component->getProp('bleed'))->toBeTrue()
        ->and($component->getAttribute('bleed'))->toBeNull()
        ->and($component->getProps())->toBe(['bleed' => true]);
});

it('leaves props out of the exported attributes', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::TABLE))
        ->setAttribute('id', 'orders')
        ->setProp('bleed', true);

    expect($component->toArray()['attributes'])->toBe(['id' => 'orders'])
        ->and($component->getProps())->toBe(['bleed' => true]);
});

it('merges attributes and props in the attribute bag with props winning', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::TABLE))
        ->setAttribute('id', 'orders')
        ->setProp('bleed', true);

    expect($component->getAttributeBag()->getAttributesAndProps())
        ->toBe(['id' => 'orders', 'bleed' => true]);
});

it('renders boolean props', function () {
    $html = (new FluxBackendComponent(FluxComponentEnum::TABLE))
        ->setProp('bleed', true)
        ->toHtml();

    expect($html)->toContain('--flux-bleed');
});

it('holds array and object props outside the export', function () {
    $paginator = new stdClass;

    $component = (new FluxBackendComponent(FluxComponentEnum::TABLE))
        ->setProp('rows', ['Alice'])
        ->setProp('paginator', $paginator);

    expect($component->getProp('rows'))->toBe(['Alice'])
        ->and($component->getProp('paginator'))->toBe($paginator)
        ->and($component->toArray()['attributes'])->toBe([]);
});

it('accepts multiple props at once', function () {
    $paginator = new stdClass;

    $component = (new FluxBackendComponent(FluxComponentEnum::TABLE))
        ->setProps(['bleed' => true, 'rows' => ['Alice'], 'paginator' => $paginator]);

    expect($component->getProps())->toBe(['bleed' => true, 'rows' => ['Alice'], 'paginator' => $paginator])
        ->and($component->getAttributes())->toBe([]);
});

it('overwrites props per key', function () {
    $component = (new FluxBackendComponent(FluxComponentEnum::TABLE))
        ->setProps(['bleed' => true, 'sticky' => false])
        ->setProps(['bleed' => false]);

    expect($component->getProps())->toBe(['bleed' => false, 'sticky' => false]);
});

it('routes scalar and rich table attributes to their channel', function () {
    $component = FluxUITableUtil::make(head: ['Name'], body: [['Alice']])
        ->setTableAttributes(['id' => 'orders', 'bleed' => true])
        ->getComponent();

    expect($component->getAttribute('id'))->toBe('orders')
        ->and($component->getAttribute('bleed'))->toBeNull()
        ->and($component->getProp('bleed'))->toBeTrue();
});
