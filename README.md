# Filament Apache ECharts

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elemind/filament-echarts.svg?style=flat-square)](https://packagist.org/packages/elemind/filament-echarts)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elemind/filament-echarts/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elemind/filament-echarts/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elemind/filament-echarts/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elemind/filament-echarts/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elemind/filament-echarts.svg?style=flat-square)](https://packagist.org/packages/elemind/filament-echarts)

Strongly inspired by [Leandro Ferreira’s Apex Charts plugin](https://filamentphp.com/plugins/leandrocfe-apex-charts),
this plugin delivers Apache ECharts integration for Filament

## Installation

You can install the package via composer:

```bash
composer require elemind/filament-echarts
```

Register the plugin for the Filament Panels you want to use:

```php
use Elemind\FilamentECharts\FilamentEChartsPlugin;
public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentEChartsPlugin::make()
        ]);
}
```

## Usage

Start by creating a widget with the command:

```bash
php artisan make:filament-echarts BlogPostsChart
```

### Available chart samples

You may choose:

- Line
- Bar
- Pie
- Scatter
- Candlestick
- Radar
- Boxplot
- Sunburst
- Parallel
- Sankey
- Funnel
- Gauge

## Setting a widget title

You may set a widget title:

```php
protected static ?string $heading = 'Blog Posts Chart';
```

Optionally, you can use the `getHeading()` method.

## Setting a widget subheading

You may set a widget subheading:

```php
protected static ?string $subheading = 'This is a subheading';
```

Optionally, you can use the `getSubheading()` method.

## Setting a chart id

You may set a chart id:

```php
protected static string $chartId = 'blogPostsChart';
```

## Making a widget collapsible

You may set a widget to be collapsible:

```php
protected static bool $isCollapsible = true;
```

You can also use the `isCollapsible()` method:

```php
protected function isCollapsible(): bool
{
    return true;
}
```

## Setting a widget height

By default, the widget height is set to `300px`. You may set a custom height:

```php
protected static ?int $contentHeight = 400; //px
```

Optionally, you can use the `getContentHeight()` method.

```php
protected function getContentHeight(): ?int
{
    return 400;
}
```

## Setting a widget footer

You may set a widget footer:

```php
protected static ?string $footer = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
```

You can also use the `getFooter()` method:

Custom view:

```php
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

protected function getFooter(): null|string|Htmlable|View
{
    return view('custom-footer', ['text' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.']);
}
```

```html
<!--resources/views/custom-footer.blade.php-->
<div>
    <p class="text-danger-500">{{ $text }}</p>
</div>
```

Html string:

```php
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
protected function getFooter(): null|string|Htmlable|View
{
    return new HtmlString('<p class="text-danger-500">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>');
}
```

## Hiding header content

You can hide header content by **NOT** providing these

- $heading
- getHeading()
- $subheading
- getSubheading()
- getOptions()

## Filtering chart data

You can set up chart filters to change the data shown on chart. Commonly, this is used to change the time period that
chart data is rendered for.

### Filter schema

You may use components from the [Schemas](https://filamentphp.com/docs/4.x/schemas/overview#available-components) to
create custom filters.
You need to use `HasFiltersSchema` trait and implement the `filtersSchema()` method to define the filter form schema:

```php
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;  
use Elemind\FilamentECharts\Widgets\EChartWidget;

class BlogPostsChart extends EChartWidget 
{
    use HasFiltersSchema;
    
    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->default('Blog Posts Chart'),
                
            DatePicker::make('date_start')  
                ->default('2025-07-01'),
    
            DatePicker::make('date_end')
                ->default('2025-07-31'),
        ]);
    }
    
    /**
    * Use this method to update the chart options when the filter form is submitted.
    */
    public function updatedInteractsWithSchemas(string $statePath): void
    {
        $this->updateOptions();
    }
}
```

The data from the custom filter is available in the `$this->filters` array. You can use the active filter values within
your `getOptions()` method:

```php
protected function getOptions(): array
{
    $title = $this->filters['title'];
    $dateStart = $this->filters['date_start'];
    $dateEnd = $this->filters['date_end'];

    return [
        //chart options
    ];
}
```

### Single select

To set a default filter value, set the `$filter` property:

```php
public ?string $filter = 'today';
```

Then, define the `getFilters()` method to return an array of values and labels for your filter:

```php
protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}
```

You can use the active filter value within your `getOptions()` method:

```php
protected function getOptions(): array
{
    $activeFilter = $this->filter;

    return [
        //chart options
    ];
}
```

## Live updating (polling)

By default, chart widgets refresh their data every 5 seconds.

To customize this, you may override the `$pollingInterval` property on the class to a new interval:

```php
protected static ?string $pollingInterval = '10s';
```

Alternatively, you may disable polling altogether:

```php
protected static ?string $pollingInterval = null;
```

## Defer loading

This can be helpful when you have slow queries and you don't want to hold up the entire page load:

```php
protected static bool $deferLoading = true;

protected function getOptions(): array
{
    //showing a loading indicator immediately after the page load
    if (!$this->readyToLoad) {
        return [];
    }

    //slow query
    sleep(2);

    return [
        //chart options
    ];
}
```

## Loading indicator

You can change the loading indicator:

```php
protected static ?string $loadingIndicator = 'Loading...';
```

You can also use the `getLoadingIndicator()` method:

```php
use Illuminate\Contracts\View\View;
protected function getLoadingIndicator(): null|string|View
{
    return view('custom-loading-indicator');
}
```

```html
<!--resources/views/custom-loading-indicator.blade.php-->
<div>
    <p class="text-danger-500">Loading...</p>
</div>
```

## Dark mode

The dark mode is supported and enabled by default.

## Extra options and Formatters

You can use the `extraJsOptions` method to add additional options to the chart:

```php
use Filament\Support\RawJs;

protected function extraJsOptions(): ?RawJs
{
    return RawJs::make(<<<'JS'
        {
            yAxis: {
                axisLabel: {
                    formatter: function(value, index, extra) {
                        return value + ' votes';
                    }
                }
            }
        }
    JS);
}
```

## Publishing views

Optionally, you can publish the views using:

```bash
php artisan vendor:publish --tag="filament-echarts-views"
```

## Publishing translations

Optionally, you can publish the translations using:

```bash
php artisan vendor:publish --tag=filament-echarts-translations
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [elemind](https://github.com/elemind)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
