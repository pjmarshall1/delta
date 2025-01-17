<script setup>
import {computed, ref} from "vue";
import {useWindowSize} from "@vueuse/core";
import {Link, useForm} from "@inertiajs/vue3"

import {RiArrowLeftSLine, RiArrowRightSLine, RiCheckboxCircleFill, RiExpandDiagonalLine} from "vue-remix-icons";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CandlestickChartWrapper from "@/Components/CandlestickChart/CandlestickChartWrapper.vue";
import Card from "@/Components/Card.vue";
import List from "@/Components/List.vue";
import ScanAlertsTable from "@/Components/Tables/ScanAlertsTable.vue";
import ScanAlertsTableModal from "@/Components/Modals/ScanAlertsTableModal.vue";
import Tab from "@/Components/Tabs/Tab.vue";
import TabContainer from "@/Components/Tabs/TabContainer.vue";

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

const listItems = computed(() => {
    return [
        {label: 'Exchange', value: props.scan.exchange},
        {label: 'List Date', value: dayjs(props.scan.list_date).format('MMM DD, YYYY')},
        {label: 'Market Cap', value: numeral(props.scan.market_cap).format('0,0[.][00]a')},
        {label: 'Float', value: numeral(props.scan.float).format('0,0[.][00]a')},
        {label: 'Short Interest', value: numeral(props.scan.short_interest).format('0,0[.][00]a')},
        {label: 'Price', value: numeral(props.scan.price / 10000).format('$0,0[.][0000]')},
        {
            label: 'Gap', value: numeral(props.scan.gap_percent / 10000).format('0[.][00]%'),
            color: props.scan.gap_percent > 0 ? 'text-green-600' : 'text-red-600',
        },
    ];
})

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

                <div class="h-3/4 w-0.5 bg-gray-300"/>

                <Link :disabled="! meta.previousUrl" :href="meta.previousUrl" as="button"
                      class="p-1 rounded-full text-gray-600 hover:bg-gray-200 disabled:hover:bg-transparent disabled:text-gray-400">
                    <RiArrowLeftSLine class="mr-px w-5 h-5"/>
                </Link>
                <Link :disabled="!meta.nextUrl" :href="meta.nextUrl" as="button"
                      class="p-1 rounded-full text-gray-600 hover:bg-gray-200 disabled:hover:bg-transparent disabled:text-gray-400">
                    <RiArrowRightSLine class="ml-px w-5 h-5"/>
                </Link>
            </div>
        </template>

        <div class="h-full w-full grid grid-cols-12 gap-5">
            <Card class="col-span-4 overflow-hidden">
                <TabContainer>
                    <Tab name="Details">
                        <div class="p-3 text-sm font-semibold">
                            {{ scan.name }}
                        </div>
                        <List :items="listItems"/>
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
                <Card class="size-full max-h-719">
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
