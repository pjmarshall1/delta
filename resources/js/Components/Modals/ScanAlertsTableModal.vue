<script setup>

import Modal from "@/Components/Modal.vue";
import ScanAlertsTable from "@/Components/Tables/ScanAlertsTable.vue";

const emit = defineEmits(['close']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    alerts: {
        type: Array,
        required: true,
        default: () => [],
    }
});

</script>

<template>
    <Modal :closeable="true" :show="show" maxWidth="6xl" tile="Scan Alerts" title="Scan Alerts"
           v-on:close="emit('close')">

        <div class="max-h-96 overflow-y-auto">
            <ScanAlertsTable :alerts="props.alerts"/>
        </div>
    </Modal>
</template>
