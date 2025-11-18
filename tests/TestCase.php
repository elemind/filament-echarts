<?php

namespace Elemind\FilamentECharts\Tests;

use Elemind\FilamentECharts\FilamentEChartsServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentEChartsServiceProvider::class,
        ];

    }

    public function getEnvironmentSetUp($app): void {}
}
