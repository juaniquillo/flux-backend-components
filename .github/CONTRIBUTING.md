# Contributing

Thanks for considering a contribution! Changes are accepted via pull requests only — direct pushes to `main` are blocked.

For significant changes, please open an issue first so the approach can be discussed. We follow [SemVer](https://semver.org/), so keep each pull request focused with a coherent commit history.

## Workflow

1. Create a branch from `main`.
2. Make your changes.
3. Ensure `composer qa` is fully green (PHPStan, Rector, Pint, type coverage, Pest).
4. Open a pull request against `main`. CI runs the same `composer` scripts you run locally.

## Conventions

- Tests reference components through `FluxComponentEnum` / `FluxIconEnum` — never raw component name strings.
- Test free Flux components only (no PRO components).
- On Windows, run Pest without `--parallel` (`php vendor/bin/pest`); `composer test:unit` handles this automatically.

## After a Flux upgrade

If `livewire/flux` adds or renames icons, regenerate the icon enum and commit the result:

```bash
composer icons:enum
```

The `FluxIconEnumTest` sync test will tell you when a regen is needed.
