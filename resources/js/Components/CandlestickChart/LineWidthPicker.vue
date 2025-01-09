<script setup>
import {shallowRef, watch} from 'vue';
import {RiArrowDownSLine} from 'vue-remix-icons';
import Popover from "@/Components/Popover.vue";

const model = defineModel(1);
const activeItem = shallowRef(null);

const lineWidthItems = [
    {name: '1', value: 1},
    {name: '2', value: 2},
    {name: '3', value: 3},
];

watch(() => model.value, (value) => {
    activeItem.value = lineWidthItems.find((item) => item.value === value);
}, {immediate: true});
</script>

<template>
    <Popover :autoClose="true" popoverClasses="mt-2 w-24 bg-white">
        <template v-slot:trigger>
            <div
                class="h-7 w-full p-1 flex items-center justify-between space-x-1 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-1 focus:ring-inset focus:ring-indigo-600">
                <div :style="`height: ${model}px;`" class="w-1/2 bg-gray-700"/>
                <RiArrowDownSLine class="size-3 text-gray-900"/>
            </div>
        </template>
        <template v-slot:content>
            <ul class="py-2 flex flex-col">
                <li v-for="item in lineWidthItems" :key="item.value"
                    class="w-full p-2 flex items-center hover:bg-gray-200 group cursor-pointer"
                    @click="model = item.value;">
                    <div :style="`height: ${item.value}px;`"
                         class="w-full bg-gray-700"/>
                </li>
            </ul>
        </template>
    </Popover>
</template>
