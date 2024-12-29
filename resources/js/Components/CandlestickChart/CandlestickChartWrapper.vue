<script setup>
import {computed, ref, watch} from "vue";

import {useRangeItems} from "@/Components/CandlestickChart/Composables/useRangeItems.js";
import {useIntervalItems} from "@/Components/CandlestickChart/Composables/useIntervalItems.js";
import {useIndicatorItems} from "@/Components/CandlestickChart/Composables/useIndicatorItems.js";
import {useIndicators} from "@/Components/CandlestickChart/Composables/useIndicators.js";

import {IndicatorIcon} from "@/Components/CandlestickChart/Icons/index.js";

import Selector from "@/Components/CandlestickChart/Selector.vue";
import CandlestickChart from "@/Components/CandlestickChart/CandlestickChart.vue";
import IndicatorModal from "@/Components/CandlestickChart/Modals/IndicatorModal.vue";

const {rangeItems, activeRange} = useRangeItems();
const {intervalItems, activeInterval} = useIntervalItems();
const {indicatorItems} = useIndicatorItems();
const {addActiveIndicator} = useIndicators();

const props = defineProps({
    symbol: {
        type: String,
        required: true
    },
    date: {
        type: String,
        required: true
    },
    markers: {
        type: Array,
        default: () => []
    }
});

const fetchingData = ref(false);
const showIndicators = ref(false);

const chartData = ref([]);

const markers = computed(() => {
    return props.markers.map(marker => {
        const unit = activeInterval.value.timespan;
        const multiplier = activeInterval.value.multiplier;

        const remainder = dayjs(marker.time).get(unit) % multiplier;

        return {
            ...marker,
            time: dayjs(marker.time).startOf(unit).subtract(remainder, unit).unix() + (dayjs().utcOffset() * 60),
        }
    });
});

const fetchAggregateData = async () => {
    fetchingData.value = true;

    let startDate = dayjs(props.date).subtract(activeRange.value.multiplier, activeRange.value.timespan);

    if (activeRange.value.timespan === 'day') {
        startDate = startDate.subtract(3, 'day');
    } else {
        startDate = startDate.add(1, 'day');
    }

    axios.get(route('aggregates'), {
        params: {
            symbol: props.symbol,
            multiplier: activeInterval.value.multiplier,
            timespan: activeInterval.value.timespan,
            startDate: startDate.format('YYYY-MM-DD'),
            endDate: props.date,
        }
    }).then(response => {
        if (activeRange.value.timespan === 'day') {

            let days = Object.groupBy(response.data, item => dayjs.utc(item.time).tz('America/New_York').format('YYYY-MM-DD'));

            if (Object.keys(days).length > activeRange.value.multiplier) {
                Object.keys(days).slice(0, Object.keys(days).length - activeRange.value.multiplier).forEach(key => {
                    delete days[key];
                });
            }

            chartData.value = Object.values(days).flat().map(({time, ...rest}) => ({
                time: dayjs.utc(time).unix() + dayjs().utcOffset() * 60,
                ...rest,
            }));
        } else {
            chartData.value = response.data.map(({time, ...rest}) => ({
                time: dayjs.utc(time).unix() + dayjs().utcOffset() * 60,
                ...rest,
            }));
        }
    }).finally(() => {
        fetchingData.value = false;
    });
};

const handleIndicatorSelected = (indicator) => {
    if (indicator.name === 'EMA') {

        addActiveIndicator('EMA',
            {
                length: 9,
            },
        );
    }

    if (indicator.name === 'SMA') {
        addActiveIndicator('SMA',
            {
                length: 200,
            },
        )
    }

    if (indicator.name === 'VWAP') {
        addActiveIndicator('VWAP',);
    }
};

watch([activeRange, activeInterval], async ([newRange, newInterval], [oldRange, oldInterval]) => {
    if (newRange.timespan === 'year' && oldInterval?.timespan === 'minute') {
        activeInterval.value = intervalItems.value.find(interval => interval.name === '1d');
    } else if (newInterval.timespan === 'minute' && oldRange?.timespan === 'year') {
        activeRange.value = rangeItems.value.find(range => range.name === '5D');
    }

    if (fetchingData.value) return;

    try {
        await fetchAggregateData();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}, {immediate: true});

</script>

<template>
    <div class="relative w-full h-full flex flex-grow">
        <div class="absolute top-0 inset-x-0 h-8 px-2 flex items-center space-x-2 border-b border-gray-200">
            <div class="flex items-center justify-center px-2 space-x-2">
                <button class="text-gray-500 hover:text-gray-900" type="button"
                        @click="showIndicators = !showIndicators">
                    <IndicatorIcon class="size-4"/>
                </button>
            </div>
            <div class="w-px h-full bg-gray-200"/>
            <Selector v-show="showIndicators" :items="indicatorItems" :trackActive="false" label="Indicators"
                      @itemSelected="handleIndicatorSelected"/>
        </div>

        <div class="absolute inset-x-0 inset-y-8">
            <div class="h-full w-full">
                <div v-show="fetchingData"
                     class="absolute inset-0 z-50 bg-gray-800/25 flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-gray-400"></div>
                </div>
                <CandlestickChart :data="chartData" :interval="activeInterval" :markers="markers"
                                  :symbol="props.symbol"
                                  class="h-full w-full"/>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-8 px-2 flex items-center space-x-2 border-t border-gray-200">
            <Selector v-model="activeRange" :items="rangeItems" label="Range" up/>
            <div class="w-px h-full bg-gray-200"/>
            <Selector v-model="activeInterval" :items="intervalItems" label="Interval" up/>
        </div>
    </div>

    <IndicatorModal/>
</template>
