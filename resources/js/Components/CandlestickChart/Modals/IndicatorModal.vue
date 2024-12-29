<script setup>
import {ref, watch} from 'vue';
import {useIndicatorModal} from "@/Components/CandlestickChart/Composables/useIndicatorModal.js";
import {useIndicators} from "@/Components/CandlestickChart/Composables/useIndicators.js";

import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import Tab from "@/Components/Tabs/Tab.vue";
import TabContainer from "@/Components/Tabs/TabContainer.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import NumberInput from "@/Components/NumberInput.vue";
import LineStylePicker from "@/Components/CandlestickChart/LineStylePicker.vue";
import LineWidthPicker from "@/Components/CandlestickChart/LineWidthPicker.vue";
// import ColorPicker from "@/Components/ColorPicker.vue";
import ColorPicker from "@/Components/ColorPicker/ColorPicker.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Slider from "@/Components/Slider.vue";

const {getActiveIndicator, updateActiveIndicator} = useIndicators();
const {state} = useIndicatorModal();

const indicator = ref(null);
const activeTab = ref(0);

const handleCancel = () => {
    state.show = false;
    activeTab.value = 0;
}

const handleOK = async () => {
    updateActiveIndicator(indicator.value.id, indicator.value);

    state.show = false;
    activeTab.value = 0;
}

const visibilityItems = ref([
    {name: 'minute', min: 1, max: 59},
    {name: 'hour', min: 1, max: 23},
    {name: 'day', min: 1, max: 366},
])

watch(() => state, () => {
    if (!state.show) {
        return;
    }

    indicator.value = JSON.parse(JSON.stringify(getActiveIndicator(state.indicatorId)));
}, {deep: true, immediate: true});

</script>

<template>
    <Modal :closeable="false" :show="state.show" :title="indicator?.name" maxWidth="sm">
        <div class="px-4 py-3">
            <TabContainer :activeTab="activeTab" class="-mt-4">
                <Tab :name="indicator.name !== 'VWAP' ? 'Input & Style' : 'Style'">
                    <div v-if="indicator.name !== 'VWAP'" class="p-4 grid grid-cols-2 items-center">
                        <InputLabel value="Length"/>
                        <NumberInput v-model="indicator.input.length" :min="1"/>
                    </div>
                    <div class="p-4 grid grid-cols-2 items-center">
                        <InputLabel value="Line Style"/>
                        <div class="w-full grid grid-cols-3 gap-x-1">
                            <LineStylePicker v-model="indicator.style.lineStyle"/>
                            <LineWidthPicker v-model="indicator.style.lineWidth"/>
                            <ColorPicker v-model="indicator.style.color"/>
                        </div>
                    </div>
                </Tab>
                <Tab name="Visibility">
                    <div class="mt-4 space-y-2">
                        <div v-for="item in visibilityItems" :key="item.name"
                             class="grid grid-cols-4 gap-2 items-center">
                            <div class="h-8 flex items-center space-x-2">
                                <Checkbox :checked="indicator.visibility[item.name].visible"
                                          :disabled="indicator.name === 'VWAP' && item.name === 'day'"
                                          @update:checked="indicator.visibility[item.name].visible = !indicator.visibility[item.name].visible"/>
                                <InputLabel :class="{'opacity-50': indicator.name === 'VWAP' && item.name === 'day'}"
                                            :value="item.name + 's'"
                                            class="capitalize"/>
                            </div>

                            <Slider v-model:max="indicator.visibility[item.name].max"
                                    v-model:min="indicator.visibility[item.name].min"
                                    :disabled="!indicator.visibility[item.name].visible"
                                    :range="{min: item.min, max: item.max}" class="col-span-3"/>
                        </div>
                    </div>
                </Tab>
            </TabContainer>
        </div>
        <div class="px-4 py-3 flex items-center justify-end space-x-2">
            <SecondaryButton class="ml-3" @click="handleCancel">Cancel</SecondaryButton>
            <PrimaryButton @click="handleOK">OK</PrimaryButton>
        </div>
    </Modal>
</template>
