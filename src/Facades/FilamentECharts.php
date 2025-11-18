<?php

namespace Elemind\FilamentECharts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elemind\FilamentECharts\FilamentECharts
 */
class FilamentECharts extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Elemind\FilamentECharts\FilamentECharts::class;
    }
}
