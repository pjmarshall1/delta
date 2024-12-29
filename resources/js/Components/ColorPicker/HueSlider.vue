<script setup>

const model = defineModel({
    type: Number,
    required: true,
    default: 0,
});

const gradientStyle = `background: linear-gradient(to right, hsl(0, 100%, 50%), hsl(60, 100%, 50%), hsl(120, 100%, 50%), hsl(180, 100%, 50%), hsl(240, 100%, 50%), hsl(300, 100%, 50%), hsl(360, 100%, 50%))`;

</script>

<template>
    <div class="flex flex-col items-center">
        <!-- Hue color slider with dynamic background -->
        <input
            v-model="model"
            :style="gradientStyle"
            class="appearance-none w-full h-2 rounded-lg cursor-pointer"
            max="360"
            min="0"
            type="range"
        />
    </div>
</template>

<style scoped>
input[type="range"] {
    transition: background 0.3s ease;
}

input[type='range']::-webkit-slider-thumb {
    @apply appearance-none -mt-px size-3 bg-white rounded-full pointer-events-auto cursor-pointer shadow-sm shadow-gray-950;
}
</style>
