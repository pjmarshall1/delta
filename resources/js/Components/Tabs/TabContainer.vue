<script setup>
import {nextTick, onMounted, provide, reactive, ref, watch} from 'vue';

const tabs = reactive([]);
const activeTab = ref(0);
const indicatorStyle = ref({});

const registerTab = (name) => {
    tabs.push({name});
    return tabs.length - 1; // Return index of the tab
};

const getActiveTabIndex = () => activeTab.value;

const updateIndicator = async () => {
    await nextTick();

    const tabElements = document.querySelectorAll('.tab-header');
    if (tabElements.length > 0 && activeTab.value < tabElements.length) {
        const activeElement = tabElements[activeTab.value];
        indicatorStyle.value = {
            width: `${activeElement.offsetWidth}px`,
            transform: `translateX(${activeElement.offsetLeft}px)`,
        };
    }
};

provide('registerTab', registerTab);
provide('getActiveTabIndex', getActiveTabIndex);

onMounted(() => {
    updateIndicator();
});

watch(activeTab, () => {
    updateIndicator();
});
</script>

<template>
    <div class="relative">
        <!-- Tab Headers -->
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

            <!-- Moving Bottom Border -->
            <div
                :style="indicatorStyle"
                class="absolute -bottom-px -mt-1 h-0.5 bg-indigo-500 transition-all duration-300"
            ></div>
        </div>

        <!-- Tab Content -->
        <slot/>
    </div>
</template>
