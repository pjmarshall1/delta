<script setup>
import {computed} from 'vue';
import {useIndicators} from '@/Components/CandlestickChart/Composables/useIndicators.js';
import {useIndicatorModal} from "@/Components/CandlestickChart/Composables/useIndicatorModal.js";

import {EyeIcon, EyeOffIcon, SettingsIcon, TrashBinIcon} from "@/Components/CandlestickChart/Icons/index.js";

const {removeActiveIndicator} = useIndicators();
const {open} = useIndicatorModal();

const props = defineProps({
    data: {type: Object, required: true},
    interval: {type: Object, required: true},
    symbol: {type: String, required: true},
});

const ohlc = computed(() => props.data?.find(item => item.name === 'OHLC')?.value || {open: 0, close: 0});

const ohlcClass = computed(() => ({
    'text-green-600': ohlc.value.close > ohlc.value.open,
    'text-red-600': ohlc.value.close < ohlc.value.open,
}));

const itemIsVisibleForInterval = (item) => {
    const {min, max, visible} = item.visibility[props.interval.timespan];
    const multiplier = props.interval.multiplier;

    return visible && multiplier >= min && multiplier <= max;
};

</script>

<template>
    <div class="absolute top-0 left-0 z-10 h-12">
        <div class="flex flex-col gap-y-0.5">
            <div class="w-fit px-2 py-0.5 flex items-center space-x-2 bg-gray-300 bg-opacity-35 rounded-r-full">
                <span class="text-xs font-semibold tracking-wide text-gray-800">
                    {{ `${symbol} • ${interval.label}` }}
                </span>
            </div>

            <div v-for="(item, index) in props.data" :key="index"
                 class="w-fit bg-gray-300 bg-opacity-35 rounded-r-full cursor-default"
                 @mouseenter="item.hover = true" @mouseleave="item.hover = false">

                <div v-if="item.name === 'OHLC'">
                    <div :class="ohlcClass"
                         class="px-2 space-x-2 text-xs font-semibold tracking-wide text-gray-800">
                        <span>O {{ currency(item?.value?.open) }}</span>
                        <span>H {{ currency(item?.value?.high) }}</span>
                        <span>L {{ currency(item?.value?.low) }}</span>
                        <span>C {{ currency(item?.value?.close) }}</span>
                        <span>R {{ currency(item?.value?.high - item?.value?.low) }}</span>
                        <span>V {{ numeral(item?.value?.volume).format('0,0') }}</span>
                    </div>
                </div>

                <div v-else-if="itemIsVisibleForInterval(item)" class="pl-2 py-0.5 flex items-center space-x-2">
                    <div
                        :style="!item.hover && item.style.lineVisible && item.value > 0 ? `color: ${item.style.color};` : 'color: #4b5563;'"
                        class="text-xs font-semibold tracking-wide">
                        {{ `${item.name} ${item.input?.length ?? ''}` }}
                    </div>

                    <div
                        v-show="!item.hover && item.style.lineVisible && item.value > 0"
                        :style="`color: ${item.style.color};`"
                        class="flex items-center space-x-2 text-xs font-semibold tracking-wide text-gray-800">
                        {{ numeral(item.value).format('0.00[00]') }}
                    </div>

                    <div class="pr-1 flex items-center space-x-2">
                        <button
                            v-show="!item.style.lineVisible || item.hover || !item.value"
                            :class="{'disabled:text-yellow-500': !item.value }"
                            :disabled="!item.value"
                            class="size-3.5 p-px flex items-center justify-center rounded-full hover:text-gray-900"
                            type="button"
                            @click="item.style.lineVisible = !item.style.lineVisible">
                            <component
                                :is="item.style.lineVisible && item.value ? EyeIcon : EyeOffIcon"/>
                        </button>

                        <button v-show="item.hover"
                                class="size-3.5 p-px flex items-center justify-center rounded-full text-gray-700 hover:text-gray-900"
                                type="button"
                                @click="open(item.id)">
                            <SettingsIcon/>
                        </button>

                        <button v-show="item.hover"
                                class="size-3.5 p-px flex items-center justify-center rounded-full text-gray-700 hover:text-gray-900"
                                type="button"
                                @click="removeActiveIndicator(item.id)">
                            <TrashBinIcon/>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
