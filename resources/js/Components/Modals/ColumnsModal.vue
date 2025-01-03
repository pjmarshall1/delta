<script setup>
import {ref, watch} from "vue";
import {useScanColumns} from "@/Composables/useScanColumns.js";

import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const {allScanColumns, defaultScanColumns} = useScanColumns();
const newColumns = ref([]);

const emit = defineEmits(['onCancel', 'onUpdate']);

const handleUpdateButtonClick = () => {
    allScanColumns.value = JSON.parse(JSON.stringify(newColumns.value));

    emit('onUpdate');
};

const handleSelectAll = () => {
    newColumns.value = newColumns.value.map(column => ({
        ...column,
        visible: true,
    }));
};

const handleSelectNone = () => {
    newColumns.value = newColumns.value.map(column => ({
        ...column,
        visible: false,
    }));
};

const handleSelectDefault = () => {
    newColumns.value = allScanColumns.value.map(column => ({
        ...column,
        visible: !defaultScanColumns.includes(column.field),
    }));
};

watch(() => allScanColumns.value, (newVal) => {
    newColumns.value = JSON.parse(JSON.stringify(newVal));
}, {immediate: true});

</script>

<template>
    <Modal :closeable="false" :show="show" title="Choose Columns">
        <div class="-mx-1 p-8 border-y border-gray-300">

            <span class="text-sm text-gray-600 font-medium">Choose the columns you want too display in the table.</span>

            <div class="flex items-center space-x-3">
                <button class="space-x-3 text-sm text-gray-500 hover:text-gray-700 font-medium"
                        @click="handleSelectAll">All
                </button>
                <span class="-mt-0.5 text-xs text-gray-700">•</span>
                <button class="space-x-3 text-sm text-gray-500 hover:text-gray-700 font-medium"
                        @click="handleSelectNone">None
                </button>
                <span class="-mt-0.5 text-xs text-gray-700">•</span>
                <button class="space-x-3 text-sm text-gray-500 hover:text-gray-700 font-medium"
                        @click="handleSelectDefault">
                    Default
                </button>

            </div>
            <ul class="mt-5 grid grid-cols-3 gap-2">
                <li v-for="column in newColumns" :key="column.field" class="flex items-center">
                    <input
                        :id="column.field"
                        v-model="column.visible"
                        class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                        type="checkbox"
                    />
                    <InputLabel
                        :for="column.field"
                        :value="column.label"
                        class="pl-2 cursor-pointer"
                    />
                </li>
            </ul>
        </div>

        <div class="w-full p-4 flex items-center justify-end space-x-5">
            <SecondaryButton @click="$emit('onCancel')">Cancel</SecondaryButton>
            <PrimaryButton @click="handleUpdateButtonClick">Update</PrimaryButton>
        </div>
    </Modal>
</template>
