<?php

namespace Elemind\FilamentECharts;

use Elemind\FilamentECharts\Commands\FilamentEChartsCommand;
use Elemind\FilamentECharts\Testing\TestsFilamentECharts;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentEChartsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-echarts';

    public static string $viewNamespace = 'filament-echarts';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasCommands($this->getCommands());
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        parent::packageBooted();
        Blade::componentNamespace('Elemind\\FilamentECharts\\Components', 'filament-echarts');
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-echarts/{$file->getFilename()}"),
                ], 'filament-echarts-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentECharts);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'elemind/filament-echarts';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('filament-echarts', __DIR__ . '/../resources/dist/filament-echarts.js'),
            // Css::make('filament-echarts-styles', __DIR__ . '/../resources/dist/filament-echarts.css'),
            // Js::make('filament-echarts-scripts', __DIR__ . '/../resources/dist/filament-echarts.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentEChartsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }
}
