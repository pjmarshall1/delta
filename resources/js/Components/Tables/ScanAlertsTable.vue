<script setup>

const props = defineProps({
    alerts: {
        type: Array,
        required: true,
    },
    columns: {
        type: Array,
        default: ['date', 'time', 'symbol', 'price', 'float', 'volume', 'relative_volume_daily', 'relative_volume_five', 'gap_percent', 'change_percent', 'short_interest', 'strategy'],
    },
});

</script>

<template>
    <div class="px-8">
        <div class="flow-root">
            <div class="-mx-8">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full table-fixed">
                        <thead>
                        <tr>
                            <th v-if="props.columns.includes('date')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Date
                            </th>
                            <th v-if="props.columns.includes('time')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Time
                            </th>
                            <th v-if="props.columns.includes('symbol')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Symbol
                            </th>
                            <th v-if="props.columns.includes('price')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Price
                            </th>
                            <th v-if="props.columns.includes('float')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Float
                            </th>
                            <th v-if="props.columns.includes('volume')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Volume
                            </th>
                            <th v-if="props.columns.includes('relative_volume_daily')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Rel Vol (D)
                            </th>
                            <th v-if="props.columns.includes('relative_volume_five_minute')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Rel Vol (5m)
                            </th>
                            <th v-if="props.columns.includes('gap_percent')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Gap %
                            </th>
                            <th v-if="props.columns.includes('change_percent')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Change %
                            </th>
                            <th v-if="props.columns.includes('short_interest')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Short Interest
                            </th>
                            <th v-if="props.columns.includes('strategy')"
                                class="sticky -top-0.5 px-3 py-2.5 bg-gray-200 text-center text-xs font-semibold text-gray-500"
                                scope="col">
                                Strategy
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="alert in alerts" :key="alert.id">
                            <td v-if="props.columns.includes('date')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ dayjs(alert.timestamp).format('MM-DD-YYYY')
                                    }}</span>
                            </td>
                            <td v-if="props.columns.includes('time')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ dayjs(alert.timestamp).format('HH:mm:ss')
                                    }}</span>
                            </td>
                            <td v-if="props.columns.includes('symbol')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ alert.symbol }}</span>
                            </td>
                            <td v-if="props.columns.includes('price')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ numeral(alert.price / 10000).format('$0,0[.][0000]') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('float')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                        {{ numeral(alert.float).format('0,0[.][00]a') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('volume')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                        {{ numeral(alert.volume).format('0,0[.][00]a') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('relative_volume_daily')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ numeral(alert.relative_volume_daily).format('0,0[.][00]a') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('relative_volume_five_minute')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ numeral(alert.relative_volume_five_minute).format('0,0[.][00]a') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('gap_percent')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ numeral(alert.gap_percent / 10000).format('0[.][00]%') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('change_percent')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ numeral(alert.change_percent / 10000).format('0[.][00]%') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('short_interest')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ numeral(alert.short_interest).format('0,0[.][00]a') }}
                                </span>
                            </td>
                            <td v-if="props.columns.includes('strategy')" class="px-3 py-3">
                                <span
                                    class="block w-full text-center text-xs font-medium text-gray-500">
                                    {{ alert.strategy_name }}
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
