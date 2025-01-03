import {useLocalStorage} from '@vueuse/core';
import {nextTick, ref, watch} from 'vue';

export function useScanColumns() {
    const columns = [
        {label: 'Date', field: 'date', type: 'date'},
        {label: 'Symbol', field: 'symbol', strong: true},
        {label: 'Price', field: 'price', type: 'currency'},
        {label: 'Gap %', field: 'gap_percent', type: 'percent', sentiment: true},
        {label: 'Float', field: 'float', type: 'scaledNumber'},
        {label: 'Short Int.', field: 'short_interest', type: 'scaledNumber'},
        {label: 'Alerts', field: 'alerts_count'},
        {label: 'Reviewed', field: 'reviewed', type: 'boolean'},
        {label: 'Exchange', field: 'exchange'},
        {label: 'Market Cap', field: 'market_cap', type: 'scaledNumber'},
        {label: 'List Date', field: 'list_date', type: 'date'},
    ];

    const defaultScanColumns = ['short_interest', 'exchange', 'market_cap', 'list_date'];

    const storage = useLocalStorage('Scan.Index.Datatable', {hiddenColumns: defaultScanColumns});

    const visibleColumns = ref();

    const allScanColumns = ref(columns.map((column) => {
        return {
            ...column,
            visible: !storage.value.hiddenColumns.includes(column.field) ?? true,
        };
    }));

    const resetDefault = () => {
        storage.value.hiddenColumns = defaultScanColumns;
    };

    watch(() => allScanColumns.value, (newValue) => {
        storage.value.hiddenColumns = newValue
            .filter((column) => !column.visible)
            .map((column) => column.field);
    })

    watch(() => storage.value.hiddenColumns, (newValue) => {
        nextTick(() => {
            visibleColumns.value = columns.map((column) => ({
                ...column,
                hidden: storage.value.hiddenColumns.includes(column.field),
            }));
        });
    }, {deep: true, immediate: true});

    return {allScanColumns, defaultScanColumns, resetDefault, visibleColumns};
}
