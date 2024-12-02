<script setup>
import {useWindowSize} from "@vueuse/core";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";

const {height} = useWindowSize();

const props = defineProps({
    scans: {
        type: Object,
        required: true,
    }
});

const handleScanSelected = (scan) => {
    router.get(route('scans.show', {scan: scan.id}));
}

</script>

<template>
    <AuthenticatedLayout title="Scan Log">
        <div :style="`height: ${height - 104}px`" class="w-full">
            <Card class="h-full flex flex-col">
                <div class="w-full overflow-y-auto">
                    <div class="inline-block w-full align-middle">
                        <div class="relative">
                            <table class="w-full table-fixed">
                                <thead>
                                <tr>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">Date
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Symbol
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Prev Close
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Gap
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Float
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Short Int.
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="scan in props.scans.data" :key="scan.id"
                                    class="hover:bg-gray-100 cursor-pointer">

                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium uppercase tracking-wide text-gray-500">
                                            {{ dayjs(scan.date).format('MM/DD/YYYY') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-extrabold uppercase tracking-wide text-gray-500">
                                            {{ scan.symbol }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium uppercase tracking-wide text-gray-500">
                                            {{ numeral(scan.previous_close / 10000).format('$0,0[.][0000]') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium uppercase tracking-wide text-gray-500">
                                            {{ numeral(scan.gap_percent / 10000).format('0[.][00]%') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium tracking-wide text-gray-500">
                                            {{ numeral(scan.float).format('0,0[.][00]a') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium tracking-wide text-gray-500">
                                            {{ numeral(scan.short_interest).format('0,0[.][00]a') }}
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <Pagination :links="props.scans.links"
                            :meta="props.scans.meta"
                            :only="['scans']"
                            class="p-3 border-t"/>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
