<script setup>
import {nextTick, ref, watch} from 'vue';
import {useResizeObserver} from "@vueuse/core";
import tinycolor from "tinycolor2";

const model = defineModel('color', {
    type: String,
    required: true,
});

const props = defineProps({
    hue: {
        type: Number,
        required: true,
        default: 0,
    },
});

const canvasRef = ref(null);
const canvasContainerRef = ref(null);
const canvasSize = ref({width: 0, height: 0});

const colorLocation = ref({x: 0, y: 0});

useResizeObserver(canvasContainerRef, async (entries) => {
    const entry = entries[0]
    const {width, height} = entry.contentRect;

    canvasSize.value = {width: width, height: height}

    await nextTick();
    drawGradient();
    getColorLocation();
});

const drawGradient = () => {
    const canvas = canvasRef.value;
    if (!canvas) return;

    const ctx = canvas.getContext('2d', {willReadFrequently: true});
    if (!ctx) return;

    const width = canvas.width;
    const height = canvas.height;

    // Create gradient from white to hue color
    const gradient1 = ctx.createLinearGradient(0, 0, width, 0);
    gradient1.addColorStop(0, 'white');
    gradient1.addColorStop(1, `hsl(${props.hue}, 100%, 50%)`);
    ctx.fillStyle = gradient1;
    ctx.fillRect(0, 0, width, height);

    // Overlay gradient from transparent to black
    const gradient2 = ctx.createLinearGradient(0, 0, 0, height);
    gradient2.addColorStop(0, 'transparent');
    gradient2.addColorStop(1, 'black');
    ctx.fillStyle = gradient2;
    ctx.fillRect(0, 0, width, height);
}

const handleCanvasClick = (event) => {
    const canvas = canvasRef.value;
    if (!canvas) return;
    const ctx = canvas.getContext('2d', {willReadFrequently: true});
    if (!ctx) return;

    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    const pixel = ctx.getImageData(x, y, 1, 1).data;

    model.value = tinycolor({r: pixel[0], g: pixel[1], b: pixel[2]}).toHexString();
}

const getColorLocation = () => {
    const canvas = canvasRef.value;
    if (!canvas) return;

    const ctx = canvas.getContext('2d', {willReadFrequently: true});
    if (!ctx) return;

    const {h, s, v} = tinycolor(model.value).toHsv();

    colorLocation.value = {
        top: `${(1 - v) * 100}%`,
        left: `${s * 100}%`
    }
}

watch(() => props.hue, () => {
    drawGradient();
}, {immediate: true});

watch(() => model.value, () => {
    getColorLocation();
}, {immediate: true});

</script>

<template>
    <div ref="canvasContainerRef" class="relative h-full w-full">
        <canvas ref="canvasRef" :height="canvasSize.height" :width="canvasSize.width" class="rounded h-full width-full"
                @click="handleCanvasClick"/>
        <div
            :style="`top: ${colorLocation.top}; left: ${colorLocation.left};`"
            class="size-4 absolute rounded-full border-2 border-white shadow shadow-gray-700 pointer-events-none -translate-x-1/2 -translate-y-1/2"/>
    </div>
</template>

<style scoped>

</style>
