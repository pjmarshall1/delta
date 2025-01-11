<script setup>
import {computed, ref} from "vue";
import {useWindowSize} from "@vueuse/core";
import {router, useForm} from "@inertiajs/vue3"

import {RiArrowLeftSLine, RiArrowRightSLine, RiCheckboxCircleFill, RiExpandDiagonalLine} from "vue-remix-icons";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CandlestickChartWrapper from "@/Components/CandlestickChart/CandlestickChartWrapper.vue";
import Card from "@/Components/Card.vue";
import Separator from "@/Components/Separator.vue";
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

const updateForm = useForm({
    reviewed: props.scan.reviewed,
});

const handleToggleReviewed = () => {
    updateForm.reviewed = !updateForm.reviewed;

    updateForm.post(route('scans.update', props.scan.id));
};

</script>

<template>
    <AuthenticatedLayout
        :title="`${scan.symbol} - ${dayjs(scan.date).format('ddd, MMM DD, YYYY')}`">

        <template #toolbar>
            <div class="h-6 flex items-center space-x-2">
                <button class="flex items-center px-2 py-1 rounded-full text-gray-600 hover:bg-gray-200"
                        @click="handleToggleReviewed">
                    <RiCheckboxCircleFill :class="props.scan.reviewed ? 'text-green-500' : 'text-gray-400'"
                                          class="w-5 h-5"/>
                    <span class="block px-1 text-sm text-gray-500">{{
                            props.scan.reviewed ? 'Scan reviewed' : 'Mark as reviewed'
                        }}</span>
                </button>

                <Separator/>

                <button :disabled="! meta.previousUrl"
                        class="p-1 rounded-full text-gray-600 hover:bg-gray-200 disabled:hover:bg-transparent disabled:text-gray-400"
                        @click="router.get(meta.previousUrl)">
                    <RiArrowLeftSLine class="mr-px w-5 h-5"/>
                </button>
                <button :disabled="!meta.nextUrl"
                        class="p-1 rounded-full text-gray-600 hover:bg-gray-200 disabled:hover:bg-transparent disabled:text-gray-400"
                        @click="router.get(meta.nextUrl)">
                    <RiArrowRightSLine class="ml-px w-5 h-5"/>
                </button>
            </div>
        </template>

        <div class="h-full w-full grid grid-cols-12 gap-5">
            <Card class="col-span-4 overflow-hidden">
                <TabContainer>
                    <Tab name="Details">
                        <div class="p-3 text-sm font-semibold">
                            {{ scan.name }}
                        </div>
                        <dl class="px-2">
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">Exchange</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ scan.exchange }}
                                </dd>
                            </div>
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">List Date</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ dayjs(scan.list_date).format('MMM DD, YYYY') }}
                                </dd>
                            </div>
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">Market Cap</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ numeral(scan.market_cap).format('0,0[.][00]a') }}
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
                            <Separator orientation="horizontal"/>
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">Price</dt>
                                <dd class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ numeral(scan.price / 10000).format('$0,0[.][0000]') }}
                                </dd>
                            </div>
                            <div class="p-3 flex items-center justify-between">
                                <dt class="text-sm font-semibold tracking-wide text-gray-500">Gap</dt>
                                <dd :class="scan.gap_percent > 0 ? 'text-green-500' : 'text-red-500'"
                                    class="text-sm font-medium tracking-wide text-gray-700">
                                    {{ numeral(scan.gap_percent / 10000).format('0[.][00]%') }}
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
                    <CandlestickChartWrapper
                        :date="scan.date"
                        :markers="[markers]"
                        :symbol="props.scan.symbol"/>
                </Card>
            </div>
        </div>

        <ScanAlertsTableModal :alerts="alerts" :show="showScanTableModal"
                              v-on:close="showScanTableModal = false"/>
    </AuthenticatedLayout>
</template>
