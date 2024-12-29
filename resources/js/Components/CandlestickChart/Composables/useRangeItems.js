import {computed, ref, watch} from 'vue';
import {useLocalStorage} from '@vueuse/core';

export function useRangeItems() {
    const defaultItems = [
        {name: '1D', multiplier: '1', timespan: 'day'},
        {name: '2D', multiplier: '2', timespan: 'day'},
        {name: '5D', multiplier: '5', timespan: 'day'},
        {name: '1M', multiplier: '1', timespan: 'month'},
        {name: '3M', multiplier: '3', timespan: 'month'},
        {name: '6M', multiplier: '6', timespan: 'month'},
        {name: '1Y', multiplier: '1', timespan: 'year'},
        {name: '5Y', multiplier: '5', timespan: 'year'},
    ];

    const defaultActive = '1D';

    const storage = useLocalStorage('CandlestickChart.Range', {
        favorites: ['1D', '5D', '1M', '1Y'],
        active: defaultActive,
    });

    const rangeItems = ref(
        defaultItems.map((item) => ({
            ...item,
            label: item.label = `${item.multiplier} ${item.timespan}` + (item.multiplier > 1 ? 's' : ''),
            favorite: storage.value.favorites.includes(item.name),
        }))
    );

    watch(rangeItems, (newItems) => {
        storage.value.favorites = newItems
            .filter((item) => item.favorite)
            .map((item) => item.name);
    }, {deep: true});

    const activeRange = computed({
        get: () => {
            let active = rangeItems.value.find((item) => item.name === storage.value.active);

            if (active && !active.favorite && !active.temp) {
                active = activeRange.value = rangeItems.value.find((item) => item.name === defaultActive);
            }

            return active;
        },
        set: (newItem) => {
            storage.value.active = newItem?.name || defaultActive;
        },
    });

    return {rangeItems, activeRange};
}
