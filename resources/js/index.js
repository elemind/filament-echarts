import * as ApacheECharts from 'echarts'
import merge from 'lodash.merge'
export default function echarts({
    options,
    chartId,
    renderer,
    extraJsOptions,
}) {
    let chart = null
    return {
        options,
        chartId,
        renderer,
        extraJsOptions,
        init() {
            this.$wire.$on('updateOptions', ({ options }) => {
                this.options = merge(this.options, this.extraJsOptions)
                this.updateChart(options)
            })

            Alpine.effect(() => {
                this.$nextTick(() => {
                    if (chart === null) {
                        this.initChart()
                    } else {
                        this.updateChart(this.options)
                    }
                })
            })

            document
                .querySelectorAll('.fi-wi-chart-filter > .fi-dropdown-panel')
                .forEach((el) => {
                    el.style.zIndex = '20'
                })
        },

        initChart: function () {
            this.options = merge(this.options, this.extraJsOptions)

            chart = ApacheECharts.init(
                document.querySelector(this.chartId),
                null,
                { renderer: this.renderer },
            )

            chart.setOption(this.options)

            const resizeObserver = new ResizeObserver((entries) => {
                if (chart) {
                    for (const entry of entries) {
                        chart.resize()
                    }
                }
            })
            resizeObserver.observe(document.querySelector(this.chartId))
        },

        updateChart(options) {
            chart.setOption(options)
        },
    }
}
