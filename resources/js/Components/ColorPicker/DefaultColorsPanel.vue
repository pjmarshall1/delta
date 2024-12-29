<script setup>

const model = defineModel();

const colorItems = [
    "#e8e8e8", "#40a9ff", "#a164e5", "#ff7875", "#fff566", "#95de64",
    "#bfbfbf", "#1890ff", "#9254de", "#fd333e", "#ffec3d", "#73d13d",
    "#8c8c8c", "#096dd9", "#722ed1", "#f5222d", "#fadb14", "#52c41a",
    "#444444", "#0050b3", "#531dab", "#cf1322", "#d4b106", "#389e0d",
    "#000000", "#003a8c", "#391085", "#820014", "#876800", "#135200",
];
</script>

<template>
    <ul class="grid grid-cols-6">
        <li v-for="item in colorItems" :key="item"
            class="size-full p-px flex items-center justify-center">
            <div
                :class="item === model ? 'border-blue-500' : 'border-transparent'"
                class="size-full flex items-center justify-center border-2 rounded cursor-pointer hover:border-blue-500 overflow-hidden"
                @click="model = item">
                <div :style="`background-color: ${item};`" class="size-full"></div>
            </div>
        </li>
    </ul>
</template>

<style scoped>

</style>
