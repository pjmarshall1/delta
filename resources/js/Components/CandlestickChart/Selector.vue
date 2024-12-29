<script setup>
import {computed, nextTick, onMounted} from "vue";
import {RiArrowDropDownFill, RiArrowDropUpFill, RiStarFill} from "vue-remix-icons";
import Dropdown from "@/Components/Dropdown.vue";

const model = defineModel();

const emit = defineEmits(['itemSelected']);

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    label: {
        type: String,
        required: false,
        default: null,
    },
    up: {
        type: Boolean,
        required: false,
        default: false,
    },
    trackActive: {
        type: Boolean,
        required: false,
        default: true,
    },
});

const dropdownClasses = computed(() => {
    return `right-0 ${props.up ? 'bottom-6' : 'top-6'} w-32`;
});

const handleIndicatorSelected = (item) => {
    model.value = item;
    emit('itemSelected', item)
};

onMounted(async () => {
    await nextTick();
    const item = props.items.find(item => item.name === model.value);
    if (item && !item.favorite) {
        model.value = props.items.filter(item => item.favorite)[0]?.name;
    }
});

</script>

<template>
    <div class="relative flex items-center space-x-1">
        <span v-if="props.label" class="font-semibold tracking-wide text-xs text-gray-500">
            {{ props.label }}:
        </span>
        <div class="flex items-center space-x-1">
            <template v-for="item in props.items" :key="item.name">
                <button v-if="item.favorite || item.temp"
                        :class="[props.trackActive && item.name === model?.name ? 'text-gray-900 font-semibold' : 'text-gray-500 font-medium hover:text-gray-900',
                        'px-1 text-xs tracking-wide']"
                        @click="handleIndicatorSelected(item)">
                    {{ item.name }}
                </button>
            </template>
        </div>
        <Dropdown :autoClose="true"
                  :dropdownClasses="dropdownClasses"
                  contentClasses="py-1 bg-gray-700">
            <template #trigger="{ active }">
                <div
                    :class="[active ? 'text-gray-900' : 'text-gray-500', '-ml-1 text-xs font-semibold tracking-wide hover:text-gray-900']">
                    <RiArrowDropUpFill v-if="up" :class="{ 'rotate-180': active }"
                                       class="size-6 transition duration-300"/>
                    <RiArrowDropDownFill v-else :class="{ 'rotate-180': active }"
                                         class="size-6 transition duration-300"/>
                </div>
            </template>
            <template #content>
                <ul class="py-2 flex flex-col">
                    <li v-for="item in items" :key="item.value"
                        class="px-2 py-1 flex items-center justify-between hover:bg-gray-600 group cursor-pointer">
                        <div class="w-full text-gray-200 text-xs font-medium leading-6"
                             v-on:click="item.temp = true; model = item;">
                            <span>{{ item.label }}</span>
                        </div>
                        <div v-on:click="item.favorite = !item.favorite; item.temp = item.favorite;">
                            <RiStarFill
                                :class="[item.favorite ? 'text-yellow-300' : 'text-transparent group-hover:text-white',
                                'size-4']"/>
                        </div>
                    </li>
                </ul>
            </template>
        </Dropdown>
    </div>
</template>
