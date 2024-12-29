<script setup>
import {nextTick, onMounted, onUnmounted, reactive, ref, watch} from 'vue';
import {useResizeObserver} from '@vueuse/core';
import {createChart, CrosshairMode} from 'lightweight-charts';
import {useSessionHighlighter} from "@/Components/CandlestickChart/Composables/useSessionHighlighting.js";
import {useIndicators} from "@/Components/CandlestickChart/Composables/useIndicators.js";

import {RiArrowRightLine} from 'vue-remix-icons';

import Legend from "@/Components/CandlestickChart/Legend.vue";

const {activeIndicators, calculateIndicator} = useIndicators();

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    interval: {
        type: Object,
        required: true,
    },
    markers: {
        type: Array,
        default: () => [],
    },
    symbol: {
        type: String,
        required: true,
    },
});

const state = reactive({
    pointData: [],
    showScrollToRightButton: false,
});

let chart;
let candlestickSeries;
let volumeSeries;
let timeScale;

const indicatorSeriesMap = new Map();
const mounted = ref(false);
const parentEl = ref();

useResizeObserver(parentEl, (entries) => {
    if (!chart || !chartContainer.value) return;
    const entry = entries[0]
    const {width, height} = entry.contentRect;
    chart.resize(width, height);
})

const chartContainer = ref();

const chartOptions = {
    crosshair: {
        mode: CrosshairMode.Normal,
        vertLine: {
            color: '#94a3b8',
            labelBackgroundColor: '#9ca3af',
        },
        horzLine: {
            color: '#94a3b8',
            labelBackgroundColor: '#9ca3af',
        },
    },
    grid: {
        vertLines: {
            style: 0,
            color: '#e2e8f0',
        },
        horzLines: {
            style: 0,
            color: '#e2e8f0',
        },
    },
    handleScale: {
        axisPressedMouseMove: {
            timeScale: false,
        },
    },
    timeScale: {
        borderColor: 'rgb(241 245 249)',
        timeVisible: true,
        fixLeftEdge: true,
        fixRightEdge: true,
    },
    rightPriceScale: {
        borderColor: 'rgb(241 245 249)',
    }
}

const addCandlestickSeries = () => {
    candlestickSeries = chart.addCandlestickSeries({
        priceLineVisible: false,
        lastValueVisible: false,
    });

    const {sessionHighlighting} = useSessionHighlighter();
    candlestickSeries.attachPrimitive(sessionHighlighting);
}

const addVolumeSeries = () => {
    volumeSeries = chart.addHistogramSeries();

    volumeSeries.applyOptions({
        lastValueVisible: false,
        priceLineVisible: false,
        priceFormat: {
            type: 'volume',
        },
        priceScaleId: '',
    });

    volumeSeries.priceScale().applyOptions({
        scaleMargins: {
            top: 0.7,
            bottom: 0
        },
    });
}

const updateIndicators = () => {
    indicatorSeriesMap.forEach(series => {
        chart.removeSeries(series);
    });

    indicatorSeriesMap.clear();

    activeIndicators.value.forEach(indicator => {
        const indicatorData = calculateIndicator(indicator, props.data);

        const seriesOptions = {
            priceLineVisible: false,
            lastValueVisible: false,
            crosshairMarkerVisible: false,
            ...indicator.style,
        };

        // Check if the indicator is visible for the current interval
        const {min, max, visible} = indicator.visibility[props.interval.timespan];
        const multiplier = props.interval.multiplier;

        if (!visible || multiplier < min || multiplier > max) {
            seriesOptions.lineVisible = false;
        }

        // Add the series to the chart
        const series = chart.addLineSeries(seriesOptions);

        // Store the last point of the series for future reference
        series.lastPoint = indicatorData[indicatorData.length - 1];

        // Store the series and its corresponding name in the map
        indicatorSeriesMap.set(indicator.id, series);

        series.setData(indicatorData);
    });
}

const updateLegend = (seriesData = null) => {
    state.pointData = [];

    // Load OHLC and volume data
    const {open, high, low, close} = seriesData?.get(candlestickSeries) ?? props.data[props.data.length - 1];
    const volume = seriesData?.get(volumeSeries).value ?? props.data[props.data.length - 1].volume;

    // Add OHLC and volume data to the pointData array
    state.pointData.push({
        name: 'OHLC',
        value: {open, high, low, close, volume},
    });

    // Loop through indicatorSeriesMap to match series and extract values
    indicatorSeriesMap.forEach((series, id) => {
        const indicator = activeIndicators.value.find(indicator => indicator.id === id);
        const value = seriesData?.get(series)?.value ?? series.lastPoint?.value;

        state.pointData.push({
            ...indicator,
            value: value
        });
    });
}

const onCrosshairMove = (param) => {
    const validCrosshairPoint = !(
        param === undefined || param.time === undefined || param.point.x < 0 || param.point.y < 0
    );

    updateLegend(validCrosshairPoint ? param.seriesData : null);
}

const onVisibleRangeChange = () => {
    if (!timeScale) return;

    const {from, to} = timeScale.getVisibleLogicalRange();
    const totalBars = props.data.length;
    const barsRemaining = totalBars - to;

    const percentageScrolled = (barsRemaining / totalBars) * 100;

    state.showScrollToRightButton = Math.round(percentageScrolled) >= 5;
};

const handleScrollToRight = () => {
    if (timeScale) {
        timeScale.scrollToPosition(0, false); // Scroll to the rightmost position smoothly
    }
};

watch(() => props.data, newData => {
    if (!chart) return;

    chart.unsubscribeCrosshairMove(onCrosshairMove);

    if (!candlestickSeries) return;

    candlestickSeries.setData(newData.map(item => {
        return {
            time: item.time,
            open: item.open,
            high: item.high,
            low: item.low,
            close: item.close,
        }
    }));

    volumeSeries.setData(newData.map(item => {
        return {
            time: item.time,
            value: item.volume,
            color: item.open > item.close ? 'rgba(255, 0, 0, 0.25)' : 'rgba(38,166,154, 0.25)'
        }
    }));

    updateIndicators();
    updateLegend();

    if (!mounted.value) {
        chart.timeScale().fitContent();
        mounted.value = true;
    }

    chart.subscribeCrosshairMove(onCrosshairMove);
}, {deep: true, immediate: true});

watch(() => props.markers, markers => {
    candlestickSeries.setMarkers(markers);
}, {deep: true});

watch(() => activeIndicators.value, () => {
    if (!chart) return;
    chart.unsubscribeCrosshairMove(onCrosshairMove);
    updateIndicators();
    updateLegend();
    chart.subscribeCrosshairMove(onCrosshairMove);
}, {deep: true});

onMounted(async () => {
    await nextTick();

    chart = createChart(chartContainer.value, chartOptions);
    timeScale = chart.timeScale();
    timeScale.subscribeVisibleTimeRangeChange(onVisibleRangeChange);

    addCandlestickSeries();

    addVolumeSeries();

    await nextTick();
    candlestickSeries.setMarkers(props.markers);

});

onUnmounted(() => {
    if (chart) {
        chart.remove();
        chart = null;
    }
    if (candlestickSeries) {
        candlestickSeries = null;
    }
});
</script>

<template>
    <div ref="parentEl" class="relative w-full h-full">
        <div ref="chartContainer" class="lw-chart"></div>
        <Legend v-if="mounted"
                :data="state.pointData"
                :interval="interval"
                :symbol="symbol"
                class="mt-1"/>
        <button v-show="state.showScrollToRightButton"
                class="absolute bottom-12 right-20 z-50 size-6 flex items-center justify-center rounded-full border border-gray-700 shadow-lg hover:bg-gray-100 transition-colors duration-300"
                @click="handleScrollToRight">
            <RiArrowRightLine class="size-5 text-gray-700"/>
        </button>
    </div>
</template>

<style>
#tv-attr-logo {
    display: none;
}
</style>
