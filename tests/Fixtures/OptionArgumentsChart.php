<?php

namespace Elemind\FilamentECharts\Tests\Fixtures;

use Elemind\FilamentECharts\Widgets\EChartWidget;
use Illuminate\Contracts\View\View;

abstract class OptionArgumentsChart extends EChartWidget
{
    /**
     * @var array<int, int>
     */
    public array $data = [1, 2, 3];

    public function render(): View
    {
        return view('tests::testable-chart');
    }

    protected function getOptions(): array
    {
        return [
            'series' => [
                [
                    'type' => 'line',
                    'data' => $this->data,
                ],
            ],
        ];
    }
}
