<script setup>
import {ref, watch} from 'vue';
import NumberInput from "@/Components/NumberInput.vue";


const min = defineModel('min', {
    type: [String, Number],
    default: 0,
});

const max = defineModel('max', {
    type: [String, Number],
    default: 100,
});

const props = defineProps({
    range: {
        type: Object,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    }
});

const activeRange = ref({
    left: '0%',
    width: '100%',
});

watch([min, max], ([newMin, newMax]) => {
    const totalRange = props.range.max - props.range.min;
    const leftPercent = ((newMin - props.range.min) / totalRange) * 100;
    const widthPercent = ((newMax - newMin) / totalRange) * 100;

    activeRange.value.left = `${leftPercent}%`;
    activeRange.value.width = `${widthPercent}%`;
});

const handleMaxChange = (event) => {
    if (event.target.value <= min.value) {
        max.value = min.value;
    }
}

const handleMinChange = (event) => {
    if (event.target.value >= max.value) {
        min.value = max.value;
    }
}

const handleBarClick = (event) => {
    if (props.disabled) {
        return;
    }

    const bar = event.currentTarget;
    const rect = bar.getBoundingClientRect();
    const clickX = event.clientX - rect.left;
    const barWidth = rect.width;

    const clickValue = props.range.min + (clickX / barWidth) * (props.range.max - props.range.min);

    const distanceToMin = Math.abs(clickValue - min.value);
    const distanceToMax = Math.abs(clickValue - max.value);

    if (distanceToMin < distanceToMax) {
        min.value = Math.min(clickValue, max.value - 1);
    } else {
        max.value = Math.max(clickValue, min.value + 1);
    }
};
</script>

<template>
    <div class="w-full flex items-center space-x-2">
        <NumberInput v-model="min" :disabled="disabled" :max="props.range.max" :min="props.range.min"/>
        <div ref="container" class="relative w-full flex items-center">
            <div :class="{'opacity-50': disabled}" class="absolute h-2.5 w-full bg-gray-200 rounded-full"
                 @click="handleBarClick"/>

            <div :class="{'opacity-50': disabled}"
                 :style="{ width: activeRange.width, left: activeRange.left }"
                 class="absolute h-2.5 bg-gray-700 rounded-full"
                 @click="handleBarClick"/>

            <input id="fromSlider" ref="fromSlider" v-model="min" :disabled="disabled"
                   :max="props.range.max" :min="props.range.min"
                   type="range"
                   @input="handleMinChange"
            />

            <input id="toSlider" ref="toSlider" v-model="max" :disabled="disabled"
                   :max="props.range.max" :min="props.range.min"
                   type="range"
                   @input="handleMaxChange"
            />
        </div>
        <NumberInput v-model="max" :disabled="disabled" :max="props.range.max" :min="props.range.min"></NumberInput>
    </div>
</template>

<style scoped>
input[type='range'] {
    @apply appearance-none absolute h-2.5 w-full bg-transparent rounded-full pointer-events-none;
}

input[type='range']::-webkit-slider-thumb {
    @apply appearance-none -mt-px size-3 bg-white rounded-full pointer-events-auto shadow-sm shadow-gray-950;
}

#fromSlider {
    @apply h-0 z-10;
}
</style>
