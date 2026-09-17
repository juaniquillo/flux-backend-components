<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use Flux\FluxServiceProvider;

require __DIR__.'/../vendor/autoload.php';

$packagePath = InstalledVersions::isInstalled('livewire/flux')
    ? InstalledVersions::getInstallPath('livewire/flux')
    : dirname((new ReflectionClass(FluxServiceProvider::class))->getFileName(), 2);

$iconPath = $packagePath.'/stubs/resources/views/flux/icon';

$slugs = array_map(
    fn (string $path): string => basename($path, '.blade.php'),
    glob($iconPath.'/*.blade.php') ?: [],
);

sort($slugs);

$cases = array_map(
    fn (string $slug): string => sprintf("    case %s = 'icon.%s';", strtoupper(str_replace('-', '_', $slug)), $slug),
    $slugs,
);

$header = <<<'PHP'
<?php

declare(strict_types=1);

namespace Juaniquillo\FluxBackendComponents;

/**
 * Every icon component bundled with livewire/flux — Heroicons plus Flux-specific icons such as `loading`.
 *
 * Values are full component names, so a case can be passed straight to `FluxBackendComponent`,
 * which renders the equivalent of `<flux:icon.bolt />`:
 *
 *     new FluxBackendComponent(FluxIconEnum::BOLT);
 *     new FluxBackendComponent(FluxIconEnum::BOLT)->setAttribute('variant', 'solid');
 *
 * Icons added through `php artisan flux:icon` (Lucide) or the application's own
 * `resources/views/flux/icon` directory are not part of this enum.
 *
 * Regenerate with: composer icons:enum
 */
enum FluxIconEnum: string
{
PHP;

$contents = $header."\n".implode("\n", $cases)."\n}\n";

file_put_contents(__DIR__.'/../src/FluxIconEnum.php', $contents);

echo sprintf("Generated FluxIconEnum with %d icons.\n", count($slugs));
