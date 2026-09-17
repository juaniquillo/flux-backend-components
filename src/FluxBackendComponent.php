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
use Juaniquillo\BackendComponents\Concerns\IsThemeable;
use Juaniquillo\BackendComponents\Contracts\AttributeBag;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
use Juaniquillo\BackendComponents\Contracts\PathComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;

use function Juaniquillo\BackendComponents\isBackedEnum;

final class FluxBackendComponent implements BackendComponent, ContentComponent, Htmlable, PathComponent, ThemeComponent
{
    use HasContent,
        HasPath,
        IsBackendComponent,
        IsThemeable;

    private const string UTILITY_VIEW = 'backend-component::_utilities.resolve-third-party-component';

    public function __construct(
        private string|BackedEnum $name,
        ThemeManager $themeManager = new DefaultThemeManager,
    ) {
        $this->themeManager = $themeManager;
    }

    public function setAttribute(string $name, mixed $value): static
    {
        $this->attributes[$name] = $value;

        return $this;
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
            $this->getAttributes(),
            $this->processContent(),
            $this->compileTheme(),
            $this->getComponentPath(),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'attributes' => $this->getAttributes(),
            'content' => $this->processContent()->toArray(),
            'path' => $this->getComponentPath(),
            'theme' => [
                'manager' => $this->themeManager::class,
                'themes' => $this->getThemes(),
                'path' => $this->themeManager->getDefaultPath(),
                'realPath' => $this->themeManager->getThemePath(),
            ],
        ];
    }

    public function toHtml(): string
    {
        $path = $this->getComponentPath();
        $attributes = $this->getAttributeBag()->getAttributes();
        $attributeBag = new ComponentAttributeBag($attributes);
        $content = $this->processContent();

        return \view(self::UTILITY_VIEW)
            ->with('path', $path)
            ->with('attributes', $attributeBag)
            ->with('content', $content)
            ->render();
    }
}
