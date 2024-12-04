<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
import Tab from "@/Components/Tabs/Tab.vue";
import TabContainer from "@/Components/Tabs/TabContainer.vue";
import ScanAlertsTable from "@/Components/Tables/ScanAlertsTable.vue";

import {RiExpandDiagonalLine} from "vue-remix-icons";

const props = defineProps({
    scan: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AuthenticatedLayout
        :title="`${scan.symbol} - ${dayjs(scan.date).format('ddd, MMM DD, YYYY')}`">
        <div class="grid grid-cols-12 gap-5">
            <Card class="col-span-4">
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
                        <div class="flex items-center justify-between w-full px-5 py-3 overflow-y-auto">
                            <div class="font-medium tracking-wide text-xs text-gray-500">
                                {{ props.scan.alerts.length }}
                                alerts
                            </div>
                            <button class="border-0 flex space-x-1 font-medium tracking-wide text-xs text-gray-500">
                                <RiExpandDiagonalLine class="size-4 text-gray-500"/>

                                <span class="block">Expand</span>
                            </button>
                        </div>
                        <ScanAlertsTable :alerts="props.scan.alerts"
                                         :columns="['time', 'price', 'volume', 'gap_percent']"
                                         class="items-stretch"
                        />
                    </Tab>
                </TabContainer>
            </Card>
            <Card class="col-span-8" title="Chart">
                <div class="w-full aspect-video bg-red-500"></div>

            </Card>
        </div>
    </AuthenticatedLayout>
</template>
