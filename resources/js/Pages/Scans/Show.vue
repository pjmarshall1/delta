<script setup>
import {computed, ref} from "vue";
import {useWindowSize} from "@vueuse/core";
import {router} from "@inertiajs/vue3"

import {RiArrowLeftSLine, RiArrowRightSLine, RiExpandDiagonalLine} from "vue-remix-icons";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CandlestickChartWrapper from "@/Components/CandlestickChart/CandlestickChartWrapper.vue";
import Card from "@/Components/Card.vue";
import Tab from "@/Components/Tabs/Tab.vue";
import TabContainer from "@/Components/Tabs/TabContainer.vue";
import ScanAlertsTable from "@/Components/Tables/ScanAlertsTable.vue";
import ScanAlertsTableModal from "@/Components/Modals/ScanAlertsTableModal.vue";

const {height} = useWindowSize();

const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
    scan: {
        type: Object,
        required: true,
    },
});

const alerts = computed(() => {
    return props.scan.alerts.map(alert => {
        return {
            ...alert,
            timestamp: dayjs.utc(alert.timestamp).tz(dayjs.tz.guess()).format('MM-DD-YYYY HH:mm:ss'),
        };
    });
});

const markers = computed(() => {
    return {
        time: alerts.value[0].timestamp,
        position: 'aboveBar',
        color: '#22c55e',
        shape: 'circle',
    };
});

const showScanTableModal = ref(false);

</script>

<template>
    <AuthenticatedLayout
        :title="`${scan.symbol} - ${  dayjs(scan.date).format('ddd, MMM DD, YYYY')}`">

        <template #header>
            <div class="flex items-center space-x-2">
                <button :disabled="! meta.previousUrl"
                        class="p-1 rounded-full text-gray-600 hover:bg-gray-300 disabled:hover:bg-transparent"
                        @click="router.get(meta.previousUrl)">
                    <RiArrowLeftSLine class="mr-px w-5 h-5"/>
                </button>
                <button :disabled="!meta.nextUrl"
                        class="p-1 rounded-full text-gray-600 hover:bg-gray-300 disabled:hover:bg-transparent"
                        @click="router.get(meta.nextUrl)">
                    <RiArrowRightSLine class="ml-px w-5 h-5"/>
                </button>
            </div>
        </template>

        <div class="grid grid-cols-12 gap-5">
            <Card :style="`height: ${height - 104}px`" c class="col-span-4 overflow-hidden">
                <TabContainer>
                    <Tab name="Details">
                        <dl class="px-2">
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">Price</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ numeral(scan.price / 10000).format('$0,0[.][0000]') }}
                                </dd>
                            </div>
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">float</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ numeral(scan.gap_percent / 10000).format('0[.][00]%') }}
                                </dd>
                            </div>
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">Float</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ numeral(scan.float).format('0,0[.][00]a') }}
                                </dd>
                            </div>
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">Short Interest</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ numeral(scan.short_interest).format('0,0[.][00]a') }}
                                </dd>
                            </div>
                        </dl>
                    </Tab>
                    <Tab name="Alerts">
                        <div
                            class="flex items-center justify-between w-full px-5 py-3">
                            <div class="font-semibold tracking-wide text-xs text-gray-500">
                                {{ props.scan.alerts.length }}
                                alerts
                            </div>
                            <button
                                class="border-0 flex space-x-1 font-semibold tracking-wide text-xs text-gray-500 hover:text-gray-900"
                                v-on:click="showScanTableModal = true">
                                <RiExpandDiagonalLine class="size-4"/>
                                <span class="block">Expand</span>
                            </button>
                        </div>
                        <div
                            :style="`height: ${height - 192}px`" class="h-full overflow-y-auto">
                            <ScanAlertsTable :alerts="alerts"
                                             :columns="['time', 'price', 'volume', 'change_percent']"
                                             class="items-stretch"/>
                        </div>
                    </Tab>
                </TabContainer>
            </Card>
            <div class="col-span-8">
                <Card class="aspect-video">
                    <CandlestickChartWrapper :date="props.scan.date" :markers="[markers]"
                                             :symbol="props.scan.symbol"/>
                </Card>
            </div>
        </div>

        <ScanAlertsTableModal :alerts="alerts" :show="showScanTableModal"
                              v-on:close="showScanTableModal = false"/>
    </AuthenticatedLayout>
</template>
