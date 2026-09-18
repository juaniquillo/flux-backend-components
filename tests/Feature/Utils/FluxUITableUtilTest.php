<?php

declare(strict_types=1);

use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use Juaniquillo\BackendComponents\Utils\CellBag;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;
use Juaniquillo\FluxBackendComponents\Utils\FluxUITableUtil;

function makeFluxTableUtil(array $head, array $body): FluxUITableUtil
{
    $themeManager = (new LocalThemeManager)->setDefaultPath(
        __DIR__.'/../../fixtures/views/_themes/tailwind',
    );

    return FluxUITableUtil::make($head, $body, $themeManager);
}

it('builds a flux table component', function () {
    $component = FluxUITableUtil::make(
        head: ['Name', 'Email'],
        body: [
            ['Alice', 'alice@example.com'],
            ['Bob', 'bob@example.com'],
        ],
    )->getComponent();

    expect($component)->toBeInstanceOf(FluxBackendComponent::class)
        ->and($component->getName())->toBe('table');
});

it('renders columns directly inside thead without a wrapping row', function () {
    $html = makeFluxTableUtil(
        head: ['Name'],
        body: [['Alice']],
    )->getComponent()->toHtml();

    expect($html)->toContain('data-flux-table')
        ->toContain('data-flux-columns')
        ->toContain('data-flux-column')
        ->toContain('data-flux-rows')
        ->toContain('data-flux-row')
        ->toContain('data-flux-cell')
        ->and(substr_count($html, '<tr'))->toBe(2);
});

it('renders no columns when the head is empty', function () {
    $html = makeFluxTableUtil(
        head: [],
        body: [['Alice']],
    )->getComponent()->toHtml();

    expect($html)->toContain('data-flux-table')
        ->not->toContain('data-flux-columns');
});

it('forwards table and columns attributes to the flux components', function () {
    $html = makeFluxTableUtil(
        head: ['Name'],
        body: [['Alice']],
    )
        ->setTableAttributes(['bleed' => true])
        ->setColumnsAttributes(['sticky' => true, 'class' => 'bg-white'])
        ->getComponent()
        ->toHtml();

    expect($html)->toContain('--flux-bleed')
        ->toContain('sticky top-0 z-20')
        ->toContain('bg-white');
});

it('supports per-cell attributes like variant', function () {
    $html = makeFluxTableUtil(
        head: ['Amount'],
        body: [[['content' => '$49.00', 'attributes' => ['variant' => 'strong']]]],
    )->getComponent()->toHtml();

    expect($html)->toContain('$49.00')
        ->toContain('font-medium text-zinc-800');
});

it('supports flux components inside cell bags', function () {
    $badge = (new FluxBackendComponent(FluxComponentEnum::BADGE))->setContent('Paid');

    $html = makeFluxTableUtil(
        head: ['Status'],
        body: [[new CellBag(content: $badge, attributes: ['variant' => 'strong'])]],
    )->getComponent()->toHtml();

    expect($html)->toContain('Paid')
        ->toContain('data-flux-badge')
        ->toContain('font-medium text-zinc-800');
});
