# Flux Backend Components

This repository is a Laravel package. Keep the package focused, idiomatic, and easy for Laravel developers to install, test, and maintain.

## Package Conventions

- Use Laravel-native package APIs and the existing service provider shape before adding abstractions.
- Keep package names, namespaces, Composer metadata, and documentation aligned with `juaniquillo/flux-backend-components`.
- Add only the files and dependencies needed for the package behavior being implemented.
- Keep tests focused on observable package behavior through public APIs and service provider wiring.

## Quick Commands

- Full validation: `composer qa`
- Formatting check: `composer lint:check`
- Static analysis: `composer analyse`
- Pest tests: `composer test:unit`
- Workbench build: `composer build`
- Workbench server: `composer serve`