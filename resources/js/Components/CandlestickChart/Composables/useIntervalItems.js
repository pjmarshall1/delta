import {computed, ref, watch} from 'vue';
import {useLocalStorage} from '@vueuse/core';

export function useIntervalItems() {
    const defaultItems = [
        {name: '1m', multiplier: '1', timespan: 'minute'},
        {name: '2m', multiplier: '2', timespan: 'minute'},
        {name: '5m', multiplier: '5', timespan: 'minute'},
        {name: '10m', multiplier: '10', timespan: 'minute'},
        {name: '15m', multiplier: '15', timespan: 'minute'},
        {name: '30m', multiplier: '30', timespan: 'minute'},
        {name: '1h', multiplier: '1', timespan: 'hour'},
        {name: '4h', multiplier: '4', timespan: 'hour'},
        {name: '1d', multiplier: '1', timespan: 'day'},
    ];

    const defaultActive = '5m';

    const storage = useLocalStorage('CandlestickChart.Interval', {
        favorites: ['1m', '5m', '15m', '1d'],
        active: defaultActive,
    });

    const intervalItems = ref(
        defaultItems.map((item) => ({
            ...item,
            label: item.label = `${item.multiplier} ${item.timespan}` + (item.multiplier > 1 ? 's' : ''),
            favorite: storage.value.favorites.includes(item.name),
        }))
    );

    watch(intervalItems, (newItems) => {
        storage.value.favorites = newItems
            .filter((item) => item.favorite)
            .map((item) => item.name);
    }, {deep: true});

    const activeInterval = computed({
        get: () => {
            let active = intervalItems.value.find((item) => item.name === storage.value.active);

            if (active && !active.favorite && !active.temp) {
                active = activeInterval.value = intervalItems.value.find((item) => item.name === defaultActive);
            }

            return active;
        },
        set: (newItem) => {
            storage.value.active = newItem?.name || defaultActive;
        },
    });

    return {intervalItems, activeInterval};
}
