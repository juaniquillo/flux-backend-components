<?php

declare(strict_types=1);

namespace Juaniquillo\FluxBackendComponents;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\ComponentAttributeBag;
use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\BackendComponents\Concerns\HasContent;
use Juaniquillo\BackendComponents\Concerns\HasPath;
use Juaniquillo\BackendComponents\Concerns\IsBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\BaseBackendComponent\Concerns\HasProps;
use Juaniquillo\BaseBackendComponent\Concerns\HasSettings;
use Juaniquillo\BaseBackendComponent\Concerns\IsThemeable;
use Juaniquillo\BaseBackendComponent\Contracts\AttributeBag;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ContentComponent;
use Juaniquillo\BaseBackendComponent\Contracts\PathComponent;
use Juaniquillo\BaseBackendComponent\Contracts\PropsComponent;
use Juaniquillo\BaseBackendComponent\Contracts\SettingsComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

use function Juaniquillo\BaseBackendComponent\isBackedEnum;

/**
 * @phpstan-import-type ComponentArray from BackendComponent
 */
final class FluxBackendComponent implements BackendComponent, ContentComponent, Htmlable, PathComponent, PropsComponent, SettingsComponent, ThemeComponent
{
    use HasContent;
    use HasPath;
    use HasProps;
    use HasSettings;
    use IsBackendComponent;
    use IsThemeable;

    private const string UTILITY_VIEW = 'backend-component::_utilities.resolve-third-party-component';

    public function __construct(
        private string|BackedEnum $name,
        ThemeManager $themeManager = new DefaultThemeManager,
    ) {
        $this->themeManager = $themeManager;
    }

    /**
     * Hardcode context
     */
    public function getContext(): string
    {
        return 'flux::';
    }

    public function getName(): int|string
    {
        $name = $this->name;

        if (isBackedEnum($name)) {
            return $name->value;
        }

        return $name;
    }

    public function getAttributeBag(): AttributeBag
    {
        return new DefaultAttributeBag(
            attributes: $this->getAttributes(),
            content: $this->processContent(),
            themes: $this->compileTheme(),
            path: $this->getComponentPath(),
            settings: $this->getSettings(),
            props: $this->getProps(),
        );
    }

    /**
     * @return ComponentArray
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'component' => self::class,
            'attributes' => $this->getAttributes(),
            'contents' => $this->processContent()->toArray(),
            'theme' => [
                'manager' => $this->themeManager::class,
                'themes' => $this->getThemes(),
                'path' => $this->themeManager->getDefaultPath(),
                'realPath' => $this->themeManager->getThemePath(),
            ],
            'path' => $this->getPathOnly(),
            'settings' => $this->getSettings(),
        ];
    }

    public function toHtml(): string
    {
        $path = $this->getComponentPath();
        $attributes = $this->getAttributeBag()->getAttributesAndProps();
        $attributeBag = new ComponentAttributeBag($attributes);
        $content = $this->processContent();

        return \view(self::UTILITY_VIEW)
            ->with('path', $path)
            ->with('attributes', $attributeBag)
            ->with('content', $content)
            ->render();
    }
}
