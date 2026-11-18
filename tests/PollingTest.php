<?php

use Elemind\FilamentECharts\Tests\Fixtures\TestableChart;
use Livewire\Livewire;

it('does not pause updates while the filters dropdown is closed', function () {
    $component = Livewire::test(TestableChart::class);

    expect($component->get('dropdownOpen'))->toBeFalse();

    $component->set('data', [4, 5, 6])
        ->call('updateOptions')
        ->assertDispatched('updateOptions');
});

it('pauses updates while the filters dropdown is open', function () {
    $component = Livewire::test(TestableChart::class);

    $component->set('dropdownOpen', true)
        ->set('data', [4, 5, 6])
        ->call('updateOptions')
        ->assertNotDispatched('updateOptions');
});

it('does not dispatch when the options did not change', function () {
    $component = Livewire::test(TestableChart::class);

    $component->call('updateOptions')
        ->assertNotDispatched('updateOptions');
});
