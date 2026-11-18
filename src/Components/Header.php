<?php

namespace Elemind\FilamentECharts\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public function __construct(
        public $heading,
        public $subheading,
        public $filters,
        public $indicatorsCount,
        public $width,
        public $filterFormAccessible
    ) {}

    /**
     * Renders the view for the header component.
     */
    public function render(): View
    {
        return view('filament-echarts::widgets.components.header');
    }
}
