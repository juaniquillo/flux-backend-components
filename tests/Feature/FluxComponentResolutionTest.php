<?php

declare(strict_types=1);

use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

it('defines a unique set of component values', function () {
    $values = collect(FluxComponentEnum::cases())->pluck('value');

    expect($values)->not->toBeEmpty()
        ->and($values->unique())->toHaveCount($values->count());
});

it('resolves every flux component enum to renderable html', function (FluxComponentEnum $componentName) {
    $component = new FluxBackendComponent($componentName);

    if ($componentName === FluxComponentEnum::ICON) {
        $component->setAttribute('icon', 'check');
    }

    if ($componentName === FluxComponentEnum::SELECT_GROUP) {
        $component->setAttribute('label', 'Group');
    }

    $html = $component->toHtml();

    expect(trim($html))->not->toBeEmpty();
})->with(FluxComponentEnum::cases());
