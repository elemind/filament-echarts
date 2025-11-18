@props([
    'chartId',
    'chartOptions',
    'chartRenderer',
    'contentHeight',
    'pollingInterval',
    'loadingIndicator',
    'deferLoading',
    'readyToLoad',
    'extraJsOptions',
])

<div
    {!! $deferLoading ? ' wire:init="loadWidget" ' : '' !!} class="flex items-center justify-center filament-echarts-chart">
    @if ($readyToLoad)
        <div x-ignore x-load
             x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-echarts', 'elemind/filament-echarts') }}"
             x-data="echarts({
                options: @js($chartOptions),
                chartId: '#{{ $chartId }}',
                renderer: '{{ $chartRenderer }}',
                extraJsOptions: {{ $extraJsOptions ?? '{}' }},
            })">
        </div>
        <div wire:ignore class="w-full filament-echarts-chart-container">
            <div class="filament-echarts-chart-object" x-ref="{{ $chartId }}" id="{{ $chartId }}"
                 style="position: relative; overflow:hidden;{{ 'height: '.$contentHeight.'px;' }}">
            </div>
            <div {!! $pollingInterval ? 'wire:poll.' . $pollingInterval . '="updateOptions"' : '' !!} x-data="{}"
                 x-init="$watch('dropdownOpen', value => $wire.dropdownOpen = value)">
            </div>
        </div>
    @else
        <div class="filament-echarts-chart-loading-indicator m-auto">
            @if ($loadingIndicator)
                {!! $loadingIndicator !!}
            @else
                <x-filament::loading-indicator class="h-7 w-7 text-gray-500 dark:text-gray-400" wire:loading.delay />
            @endif
        </div>
    @endif
</div>
