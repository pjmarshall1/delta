<script setup>

import {ref, watch} from "vue";
import tinycolor from "tinycolor2";

import {RiArrowDownSLine} from "vue-remix-icons";
import Dropdown from "@/Components/Dropdown.vue";
import DefaultColorsPanel from "@/Components/ColorPicker/DefaultColorsPanel.vue";
import HuePanel from "@/Components/ColorPicker/HuePanel.vue";
import HueSlider from "@/Components/ColorPicker/HueSlider.vue";
import TextInput from "@/Components/TextInput.vue";

const model = defineModel();

const hue = ref();

watch(() => hue.value, (value) => {
    hue.value = parseInt(value);
});

watch(() => model.value, (newValue, oldValue) => {
    const isValidHex = /^#([0-9A-F]{3}){1,2}$/i.test(newValue);

    if (!isValidHex) {
        model.value = oldValue;
        return;
    }

    hue.value = tinycolor(newValue).toHsl().h;
}, {immediate: true});

</script>

<template>
    <Dropdown :autoClose="false" :dropdownClasses="'mt-1 left-0'">
        <template v-slot:trigger>
            <div
                class="h-7 w-full p-1 flex items-center justify-between rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-1 focus:ring-inset focus:ring-indigo-600">
                <div :style="`background-color: ${model};`"
                     class="h-full aspect-square border border-gray-200 rounded">
                </div>
                <RiArrowDownSLine class="size-3 text-gray-900"/>
            </div>
        </template>
        <template v-slot:content>
            <div class="w-[400px] h-[152px] p-2 grid grid-cols-12 gap-x-2">
                <DefaultColorsPanel v-model="model" class="col-span-5"/>

                <div class="col-span-4 py-1 flex flex-col">
                    <HuePanel v-model:color="model" :hue="hue"/>
                    <HueSlider v-model="hue" class="mt-2"/>
                </div>

                <div class="col-span-3 py-1 flex flex-col">
                    <div class="h-full w-full rounded-md border border-gray-200">
                        <div :style="`background-color: ${model};`"
                             class="h-full w-full rounded-md"/>
                    </div>
                    <TextInput v-model="model" class="mt-2 h-6 text-sm"/>
                </div>
            </div>
        </template>
    </Dropdown>
</template>

<style scoped>

</style>
