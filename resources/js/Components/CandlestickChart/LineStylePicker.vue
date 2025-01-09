<script setup>
import {shallowRef, watch} from 'vue';
import {RiArrowDownSLine} from 'vue-remix-icons';
import Popover from "@/Components/Popover.vue";

const model = defineModel(0);
const activeItem = shallowRef(null);

const lineStyleItems = [
    {value: 0, style: 'border-solid'},
    {value: 3, style: 'border-dashed'},
    {value: 4, style: 'border-dotted'},
];

watch(() => model.value, (value) => {
    activeItem.value = lineStyleItems.find((item) => item.value === value);
}, {immediate: true});

</script>

<template>
    <Popover :autoClose="true" popoverClasses="mt-2 w-24 bg-white">
        <template v-slot:trigger>
            <div
                class="h-7 w-full p-1 flex items-center justify-between space-x-1 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-1 focus:ring-inset focus:ring-indigo-600">
                <div :class="activeItem.style" class="w-1/2 border-b border-gray-900"/>
                <RiArrowDownSLine class="size-3 text-gray-900"/>
            </div>
        </template>
        <template v-slot:content>
            <ul class="py-2 flex flex-col">
                <li v-for="item in lineStyleItems" :key="item.value"
                    class="w-full p-2 flex items-center hover:bg-gray-200 group cursor-pointer"
                    @click="model = item.value;">
                    <div :class="item.style" class="w-full border-b border-gray-900"/>
                </li>
            </ul>
        </template>
    </Popover>
</template>

<style scoped>

</style>
