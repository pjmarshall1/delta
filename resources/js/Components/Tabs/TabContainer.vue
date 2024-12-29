<script setup>
import {provide, reactive, ref} from 'vue';
import {useResizeObserver} from "@vueuse/core";

const props = defineProps({
    activeTab: {
        type: Number,
        default: 0,
    }
});

const tabs = reactive([]);
const activeTab = ref(props.activeTab);

const container = ref();
const tabWidth = ref(0);
useResizeObserver(container, (entries) => {
    const {width, height} = entries[0].contentRect;
    tabWidth.value = width / tabs.length;
});

const registerTab = (name) => {
    tabs.push({name});
    return tabs.length - 1; // Return index of the tab
};

const getActiveTabIndex = () => activeTab.value;

provide('registerTab', registerTab);
provide('getActiveTabIndex', getActiveTabIndex);

</script>

<template>
    <div ref="container" class="relative">
        <div class="relative h-12 flex items-center border-b ">
            <button
                v-for="(tab, index) in tabs"
                :key="index"
                :class="activeTab === index ? 'text-gray-800' : 'text-gray-500 hover:text-gray-800'"
                class="tab-header flex-1 text-center px-4 py-2 text-sm font-medium transition relative"
                @click="activeTab = index"
            >
                {{ tab.name }}
            </button>

            <div
                :style="`width: ${tabWidth}px; left: ${activeTab * tabWidth}px`"
                class="absolute -bottom-px -mt-1 h-0.5 bg-indigo-500 transition-all duration-300"
            ></div>
        </div>

        <slot/>
    </div>
</template>
