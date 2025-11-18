<?php

namespace Elemind\FilamentECharts\Widgets;

use Elemind\FilamentECharts\Concerns\CanDeferLoading;
use Elemind\FilamentECharts\Concerns\CanFilter;
use Elemind\FilamentECharts\Concerns\HasContentHeight;
use Elemind\FilamentECharts\Concerns\HasFooter;
use Elemind\FilamentECharts\Concerns\HasHeader;
use Elemind\FilamentECharts\Concerns\HasLoadingIndicator;
use Elemind\FilamentECharts\Concerns\HasRenderer;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class EChartWidget extends Widget implements HasSchemas
{
    use CanDeferLoading;
    use CanFilter;
    use CanPoll;
    use HasContentHeight;
    use HasFooter;
    use HasHeader;
    use HasLoadingIndicator;
    use HasRenderer;

    protected static ?string $chartId = null;

    // @phpstan-ignore-next-line
    protected string $view = 'filament-echarts::widgets.echart-widget';

    public ?array $options = null;

    public function mount(): void
    {
        if (method_exists($this, 'getFiltersSchema')) {
            $this->getFiltersSchema()->fill();
        }

        $this->options = $this->getOptions();

        if (! $this->getDeferLoading()) {
            $this->readyToLoad = true;
        }
    }

    public function on(): void {}

    public function render(): View
    {
        return view($this->view, []);
    }

    protected function getChartId(): ?string
    {
        return static::$chartId ?? 'eChart_' . Str::random(10);
    }

    /**
     * Returns an array of chart options for displaying a line chart of customer data.
     *
     * @return array Array of chart options
     */
    protected function getOptions(): array
    {
        return [];
    }

    public function updateOptions(): void
    {
        if ($this->options !== $this->getOptions()) {

            $this->options = $this->getOptions();

            if (! $this->dropdownOpen) {
                $this
                    ->dispatch('updateOptions', options: $this->options)
                    ->self();
            }
        }
    }

    protected function extraJsOptions(): ?RawJs
    {
        return null;
    }
}
