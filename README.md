# Flux Backend Components

<p >
    <a href="https://packagist.org/packages/juaniquillo/flux-backend-components"><img src="https://img.shields.io/packagist/v/juaniquillo/flux-backend-components.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/juaniquillo/flux-backend-components"><img src="https://img.shields.io/packagist/php-v/juaniquillo/flux-backend-components.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://github.com/juaniquillo/flux-backend-components/actions/workflows/ci.yml"><img alt="CI status (main)" src="https://img.shields.io/github/actions/workflow/status/juaniquillo/flux-backend-components/ci.yml?branch=main&label=CI&style=flat-square"></a>
</p>

Build [Flux UI](https://fluxui.dev) interfaces from PHP. This package lets you compose Flux components (free edition) in backend code — with content, attributes, and Tailwind themes — and render them anywhere Blade renders.

## Requirements

- PHP `^8.3`
- Laravel 12 or 13
- [Livewire Flux](https://fluxui.dev) `^2` (free edition components)

## Installation

You can install the package via Composer:

```bash
composer require juaniquillo/flux-backend-components
```

The package's service provider is discovered automatically by Laravel.

No Tailwind configuration is needed: component styling ships with Flux itself, and app theme files live in your already-scanned `resources/views` directory.

## Built on Laravel Backend Components

This package is a Flux-flavored adapter over [juaniquillo/laravel-backend-component](https://github.com/juaniquillo/laravel-backend-component) ([Packagist](https://packagist.org/packages/juaniquillo/laravel-backend-component)), which provides the underlying engine: the component model, theme managers, `CellBag`, and the generic `TableUtil`. It is installed automatically as a dependency — no separate setup needed.

What this package adds on top:

- `FluxBackendComponent` — the component class, hardcoded to the `flux::` view context
- `FluxComponentEnum` — every free Flux component as a typed case (`BUTTON`, `TEXT_INPUT`, `TABLE`, …)
- `FluxIconEnum` — every icon bundled with Flux as a standalone component name
- `FluxUITableUtil` — builds complete Flux tables from head/body arrays
- `FluxComponentBuilder` / `FluxLocalThemeComponentBuilder` — fluent factories, including app-local theme resolution

For the full mechanics (theming file format, serialization, the Blade rendering pipeline, helpers) see the [Laravel Backend Components documentation](https://github.com/juaniquillo/backend-component-docs).

## Usage

Component props (`variant`, `size`, `sortable`, …) are passed as attributes when scalar, or as props when rich — see the [Flux documentation](https://fluxui.dev) for each component's available props.

### Components

Pick a component from the enum, set content and attributes, and render it. Components implement Laravel's `Htmlable` contract, so Blade renders them unescaped:

```php
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

$button = (new FluxBackendComponent(FluxComponentEnum::BUTTON))
    ->setContent('Save changes')
    ->setAttributes(['id' => 'save-btn', 'variant' => 'primary']);
```

```blade
{{ $button }}
```

Or render to a string anywhere:

```php
$html = $button->toHtml();
```

Components nest via `setContents()`:

```php
$card = (new FluxBackendComponent(FluxComponentEnum::CARD))
    ->setContents([
        (new FluxBackendComponent(FluxComponentEnum::HEADING))->setContent('Recent customers'),
        (new FluxBackendComponent(FluxComponentEnum::TEXT))->setContent('Your latest customer activity.'),
        (new FluxBackendComponent(FluxComponentEnum::BUTTON))->setContent('Add customer'),
    ]);
```

The enum covers the free Flux set — general components (`CARD`, `BUTTON`, `BADGE`, `LINK`, `SEPARATOR`), layout (`HEADER`, `ASIDE`, `MAIN`, `FOOTER`, `CONTAINER`), forms (`TEXT_INPUT`, `TEXTAREA`, `SELECT`, `CHECKBOX`, `RADIO`, `SWITCH`, `FIELD`, `ERROR`), navigation (`NAVBAR`, `NAVLIST`, `SIDEBAR` and their subcomponents), `TABLE` and friends, `MODAL`, `TOAST`, `TOOLTIP`, and more.

### Builders

Fluent factories are available when you prefer them over `new`:

```php
use Juaniquillo\FluxBackendComponents\Builders\FluxComponentBuilder;
use Juaniquillo\FluxBackendComponents\Builders\FluxLocalThemeComponentBuilder;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

$button = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
    ->setContent('Save changes');

// Resolves themes from your app's views instead of the package defaults.
$themed = FluxLocalThemeComponentBuilder::make(FluxComponentEnum::BUTTON)
    ->setTheme('color', 'success');
```

### Icons

Every icon bundled with Flux (Heroicons plus Flux's own, e.g. `loading`) is a case on `FluxIconEnum`, usable as a standalone component — the equivalent of `<flux:icon.bolt />`:

```php
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxIconEnum;

$icon = new FluxBackendComponent(FluxIconEnum::BOLT);

// Pick a variant: outline (default), solid, mini, or micro.
$icon->setAttribute('variant', 'solid');
```

For the dynamic form (`<flux:icon name="…">`), use `FluxComponentEnum::ICON` with the icon name as an attribute.

### Props

Scalar values travel as attributes, keeping the base contract intact. Anything richer — booleans, arrays, collections, paginators — travels as props:

```php
$table = (new FluxBackendComponent(FluxComponentEnum::TABLE))
    ->setAttribute('id', 'orders')   // scalar attribute
    ->setProp('bleed', true)         // rich prop
    ->setProps(['rows' => $orders]); // …or several at once
```

Props merge into the rendered output (winning over attributes on collision) but stay out of `toArray()`, keeping exports JSON-safe and round-trippable.

### Tables

`FluxUITableUtil` is a helper that builds a complete `<flux:table>` tree from plain head/body arrays — the fastest path for data-driven tables. Cells accept plain values, component instances, `CellBag` objects (for per-cell themes and attributes), or `['content' => …, 'theme' => …, 'attributes' => …]` arrays:

```php
use Juaniquillo\BackendComponents\Utils\CellBag;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;
use Juaniquillo\FluxBackendComponents\Utils\FluxUITableUtil;

$table = FluxUITableUtil::make(
    head: ['Customer', 'Status'],
    body: [
        [
            'Lindsey Aminoff',
            new CellBag(
                content: (new FluxBackendComponent(FluxComponentEnum::BADGE))->setContent('Paid'),
                attributes: ['variant' => 'strong'],
            ),
        ],
    ],
)
    ->setTableAttributes(['bleed' => true])
    ->setColumnsAttributes(['sticky' => true])
    ->getComponent();
```

Per-section themes are available via `setTableThemes()`, `setThThemes()`, `setTrThemes()`, and `setTdThemes()`.

For full control, tables can also be composed by hand with `FluxBackendComponent` — the helper above is shorthand for this:

```php
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;

$table = (new FluxBackendComponent(FluxComponentEnum::TABLE))
    ->setContents([
        (new FluxBackendComponent(FluxComponentEnum::THEAD))
            ->setContents([
                (new FluxBackendComponent(FluxComponentEnum::TH))->setContent('Customer'),
                (new FluxBackendComponent(FluxComponentEnum::TH))->setContent('Status'),
            ]),
        (new FluxBackendComponent(FluxComponentEnum::TBODY))
            ->setContents([
                (new FluxBackendComponent(FluxComponentEnum::TR))
                    ->setContents([
                        (new FluxBackendComponent(FluxComponentEnum::TD))->setContent('Lindsey Aminoff'),
                        (new FluxBackendComponent(FluxComponentEnum::TD))
                            ->setAttribute('variant', 'strong')
                            ->setContent(
                                (new FluxBackendComponent(FluxComponentEnum::BADGE))->setContent('Paid')
                            ),
                    ]),
            ]),
    ]);
```

Note the columns go directly inside `THEAD` — it renders its own header row, so no `TR` wrapper is needed there.

Current limitations: per-row `key` values and the table's named `header`/`footer` slots have no builder API yet.

### Themes

Apply Tailwind theme variants with `setTheme()` / `setThemes()`:

```php
$button = FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
    ->setTheme('color', 'success');
```

Theme files live in your app's `resources/views/_themes/tailwind/` directory (one Blade file per theme group returning a variant array), resolved through the local theme builders and `FluxUITableUtil`.

## Testing

Individual checks:

- `composer analyse` — PHPStan static analysis over `src/`.
- `composer rector:check` — Rector dry-run over `src/` (use `composer rector` to apply fixes).
- `composer lint:check` — Pint style check over `src/` (use `composer lint` to fix).
- `composer test:types` — enforces 100% type coverage.
- `composer test:unit` — the Pest suite (parallel everywhere except Windows, where it runs serially to avoid file-lock collisions).

Or run the whole gate at once — the same scripts CI executes, in order:

```bash
composer qa
```

## Contributing

Thank you for considering contributing to Flux Backend Components! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Credits

- [Juaniquillo](https://github.com/juaniquillo)
- [All Contributors](https://github.com/juaniquillo/flux-backend-components/graphs/contributors)

## License

Flux Backend Components is open-sourced software licensed under the [MIT license](LICENSE.md).
