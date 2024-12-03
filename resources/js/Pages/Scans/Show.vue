<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";

const props = defineProps({
    scan: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AuthenticatedLayout :title="`${scan.symbol} - ${dayjs(scan.date).format('ddd, MMM DD, YYYY')}`">
        <div class="grid grid-cols-12 gap-5">
            <Card class="col-span-4" title="Details">
                <dl class="px-2">
                    <div class="p-3 flex items-center justify-between">
                        <dt class="text-sm font-semibold tracking-wide text-gray-500">Previous Close</dt>
                        <dd class="text-sm font-medium tracking-wide text-gray-700">
                            {{ numeral(scan.previous_close / 10000).format('$0,0[.][0000]') }}
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
            </Card>
            <Card class="col-span-8 aspect-square" title="Chart">
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
