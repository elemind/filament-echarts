# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`elemind/filament-echarts` — a Filament (v4/v5) plugin package that wraps [Apache ECharts](https://echarts.apache.org/en/option.html) as a Filament widget. It is a library, not an app: there is no host Laravel application here, tests run through Orchestra Testbench.

## Commands

```bash
composer test                # Pest test suite
vendor/bin/pest tests/ExampleTest.php          # single file
vendor/bin/pest --filter="can test"            # single test by name
composer lint                # Pint (write)
composer test:lint           # Pint (check only, what CI runs)
composer refactor            # Rector (write)
composer test:refactor       # Rector dry-run
vendor/bin/phpstan analyse   # Larastan level 4 over src/ + config/

npm run build                # esbuild bundle -> resources/dist/filament-echarts.js
npm run dev                  # same, watch mode
```

`resources/dist/filament-echarts.js` is committed. **Any change to `resources/js/index.js` requires re-running `npm run build` and committing the dist file**, otherwise the plugin ships stale JS.

CI matrix: PHP 8.2–8.4 × Laravel 11/12 × prefer-lowest/prefer-stable, on Ubuntu and Windows.

## Architecture

The whole plugin is one widget class plus a JS Alpine component; everything else is plumbing.

**PHP side — `src/Widgets/EChartWidget.php`** is the class users extend. It is a plain Filament `Widget` (not `ChartWidget`) implementing `HasSchemas`. Its behaviour is composed entirely from single-responsibility traits in `src/Concerns/`, each backed by a `protected static` property with a `getX()` accessor so subclasses can override either the property or the method:

| Trait | Property | Purpose |
|---|---|---|
| `HasHeader` | `$heading`, `$subheading`, `$isCollapsible` | section header |
| `HasContentHeight` | `$contentHeight` (300) | chart px height |
| `HasRenderer` | `$renderer` (`canvas`) | ECharts renderer (`canvas`/`svg`) |
| `HasFooter` | `$footer` | footer, may return View/Htmlable |
| `HasLoadingIndicator` | `$loadingIndicator` | custom loading markup |
| `CanDeferLoading` | `$deferLoading`, `$readyToLoad` | `wire:init="loadWidget"` |
| `CanFilter` | `$filter`, `$filterFormWidth`, `$dropdownOpen` | simple select filter + schema filters |
| `CanPoll` (Filament) | `$pollingInterval` | `wire:poll="updateOptions"` |

Subclasses only implement `getOptions(): array`, which returns a raw ECharts option array (PHP array → JSON → `chart.setOption`).

**Update flow.** `mount()` fills the filter schema and caches `$this->options`. `updateOptions()` recomputes `getOptions()`, and only if the result differs *and* the filter dropdown is closed (`$dropdownOpen`) does it `dispatch('updateOptions', ...)->self()`. Suppressing dispatch while the dropdown is open prevents Livewire re-renders from closing it mid-edit — `$dropdownOpen` is synced back from Alpine via `x-init="$watch(...)"` in the chart component, and the `echarts-dropdown` browser event resets it after submit/reset.

**JS side — `resources/js/index.js`** exports the `echarts()` Alpine component registered as a Filament `AlpineComponent` asset (lazily loaded via `x-load` / `FilamentAsset::getAlpineComponentSrc`). It listens for the `updateOptions` Livewire event, `lodash.merge`s `extraJsOptions` (a `RawJs` blob, the only way to pass real JS functions such as axis formatters) over the PHP options, and drives one ECharts instance wrapped in a `ResizeObserver`.

**Blade — `resources/views/widgets/`.** `echart-widget.blade.php` is the widget root: it resolves every `getX()` accessor into locals, renders the Filament section, wires the filter select / filter dropdown into `afterHeader`, and delegates the canvas to `<x-filament-echarts::chart>`. Class-based components live in `src/Components/` and are bound to the `filament-echarts::` namespace by the service provider. `Header` and `FilterForm` components/views exist but the current widget markup does not use them — only `Chart` is on the live path.

**Registration — `FilamentEChartsServiceProvider`** (spatie/laravel-package-tools) registers config, views, translations, the Alpine asset under package name `elemind/filament-echarts`, the Blade component namespace, the `make:filament-echarts` command, publishable stubs, and Livewire test mixins. `FilamentEChartsPlugin` is an empty `Plugin` shell — it exists so users can register the package per panel; do not put asset registration there.

**Scaffolding — `src/Commands/FilamentEChartsCommand.php`** (`make:filament-echarts`) prompts for a chart type from `config/filament-echarts.php` `chart_options`, then copies the matching `stubs/<Type>.stub` replacing `$NAMESPACE$`, `$CLASS_NAME$`, `$CHART_ID$`. Adding a chart type means adding both a `stubs/<Type>.stub` and an entry in the config array — the config list drives the prompt, the stub filename must match it exactly. Note `Parallel`, `Sankey` are listed in config/README but have no `.stub` file, so selecting them fails.

## Conventions

- Pint preset `laravel` with the tweaks in `pint.json`; Rector runs dead-code/code-quality/type-declaration/privatization/early-return/strict-boolean sets over `src/` only. Run both before committing PHP.
- PHPStan is level 4 with a `phpstan-baseline.neon`; prefer fixing over expanding the baseline.
- `tests/ArchTest.php` fails the suite if `dd`, `dump`, or `ray` appear anywhere in the package.
- Prettier (`.prettierrc`) formats the JS; Node version pinned in `.nvmrc`.
