<?php

namespace Elemind\FilamentECharts\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterForm extends Component
{
    public function __construct(
        public $indicatorsCount,
        public $width
    ) {}

    /**
     * Renders the view for the filter-form component.
     */
    public function render(): View
    {
        return view('filament-echarts::widgets.components.filter-form');
    }
}
