<?php

use Elemind\FilamentECharts\Tests\Fixtures\DefaultChart;
use Elemind\FilamentECharts\Tests\Fixtures\ReplaceMergeChart;
use Livewire\Livewire;

it('passes no setOption arguments by default', function () {
    Livewire::test(DefaultChart::class)
        ->set('dropdownOpen', false)
        ->set('data', [4, 5, 6])
        ->call('updateOptions')
        ->assertDispatched('updateOptions', setOptionOpts: []);
});

it('passes the setOption arguments defined by the widget', function () {
    Livewire::test(ReplaceMergeChart::class)
        ->set('dropdownOpen', false)
        ->set('data', [4, 5, 6])
        ->call('updateOptions')
        ->assertDispatched(
            'updateOptions',
            options: [
                'series' => [
                    [
                        'type' => 'line',
                        'data' => [4, 5, 6],
                    ],
                ],
            ],
            setOptionOpts: ['replaceMerge' => ['series']],
        );
});
