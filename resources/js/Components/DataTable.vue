<script setup>
import {computed, reactive} from 'vue';

const emit = defineEmits(['rowSelected', 'sortChanged'])

const selectedRows = defineModel('selectedRows', {
    type: Array,
    default: () => [],
});

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    hasCheckbox: {
        type: Boolean,
        default: false,
    },
    rows: {
        type: Array,
        required: true,
    },
    sortable: {
        type: Boolean,
        default: false,
    },
});

const indeterminate = computed(() => selectedRows.value.length > 0 && selectedRows.value.length < props.rows.length)
const sort = reactive({
    field: '',
    direction: '',
})

const formatValue = (type, value) => {
    switch (type) {
        case 'boolean':
            return value ? 'Yes' : 'No';
        case 'number':
            return numeral(value).format('0,0[.][00]');
        case 'scaledNumber':
            return numeral(value).format('0,0[.][00]a');
        case 'date':
            return dayjs(value).format('MM/DD/YYYY');
        case 'time':
            return dayjs(value).format('HH:mm:ss');
        case 'datetime':
            return dayjs(value).format('MM/DD/YYYY HH:mm:ss');
        case 'currency':
            return numeral(value / 10000).format('$0,0.00[00]');
        case 'percent':
            return numeral(value / 10000).format('0[.][00]%');
        default:
            return value;
    }
};

const handleSortClick = (column) => {
    if (!props.sortable) {
        return;
    }

    if (sort.field === column.field) {
        sort.direction = sort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        sort.field = column.field;
        sort.direction = 'asc';
    }

    emit('sortChanged', sort);
};

const handleRowClick = (row) => {
    emit('rowSelected', row);
};

</script>

<template>
    <table class="w-full table-fixed">
        <thead>
        <tr>
            <th v-if="hasCheckbox"
                class="sticky top-0 z-20 size-8 items-center bg-gray-200 text-center text-xs tracking-wide font-semibold text-gray-700"
                scope="col">
                <input :checked="indeterminate || selectedRows.length === rows.length"
                       :indeterminate="indeterminate"
                       class="absolute z-10 left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-transparent"
                       type="checkbox"
                       @change="selectedRows = $event.target.checked ? rows.map((r) => r.id) : []"/>
            </th>
            <template v-for="column in columns" :key="column.field">
                <th v-show="!column.hidden"
                    class="sticky top-0 h-12 items-center bg-gray-200 text-smaller text-center tracking-wide font-semibold text-gray-700"
                    scope="col">
                    <div class="flex items-center justify-center space-x-1">
                        <div
                            :class="[sortable ? 'cursor-pointer' : 'cursor-default' ,'relative flex items-center justify-center']"
                            @click="handleSortClick(column)">
                            <span>{{ column.label }}</span>
                            <div v-if="sortable" v-show="sort.field === column.field"
                                 class="absolute -right-5 -mt-0.5">
                                <svg :class="sort.direction === 'asc' ? 'rotate-0' : 'rotate-180'"
                                     class="size-5 transition-all duration-300"
                                     fill="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 10L16 14H8L12 10Z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </th>
            </template>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
        <tr v-for="row in rows" :key="row.id"
            :class="[selectedRows.includes(row.id) ? 'bg-indigo-50' : 'bg-white', 'hover:bg-gray-100']">
            <td v-if="hasCheckbox" class="relative h-12 px-7">
                <div v-if="selectedRows.includes(row.id)"
                     class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"></div>
                <input v-model="selectedRows" :value="row.id"
                       class="cursor-pointer absolute left-4 top-1/2 z-10 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-transparent"
                       type="checkbox"/>
            </td>
            <template v-for="column in columns">
                <td v-show="!column.hidden" class="h-12 cursor-pointer"
                    @click="handleRowClick(row.id)">
                    <slot :data="row[column.field]" :name="[column.field]">
                        <span
                            :class="{'text-green-700': column.sentiment && row[column.field] > 0,
                            'text-red-700': column.sentiment && row[column.field] < 0,
                            'text-gray-900 font-bold': column.strong, 'text-gray-700 font-medium': !column.strong}"
                            class="block w-full text-xs text-center">
                            {{ formatValue(column.type, row[column.field]) }}
                        </span>
                    </slot>
                </td>
            </template>
        </tr>
        </tbody>
    </table>
</template>
