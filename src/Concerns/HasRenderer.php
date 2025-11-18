<?php

namespace Elemind\FilamentECharts\Concerns;

trait HasRenderer
{
    protected static string $renderer = 'canvas';

    /**
     * Retrieves the value of the static property $darkMode.
     *
     * @return string The value of the $renderer property
     */
    protected function getRenderer(): string
    {
        return static::$renderer;
    }
}
