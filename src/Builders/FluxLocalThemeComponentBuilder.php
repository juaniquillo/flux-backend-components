<?php

declare(strict_types=1);

namespace Juaniquillo\FluxBackendComponents\Builders;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\StaticBuilder;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use Juaniquillo\FluxBackendComponents\FluxBackendComponent;

class FluxLocalThemeComponentBuilder implements StaticBuilder
{
    public static function make(string|BackedEnum $name): Htmlable|CompoundComponent
    {
        $builder = new FluxBackendComponent($name, new LocalThemeManager);

        return $builder;
    }
}
