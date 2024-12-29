import {computed, ref, watch} from 'vue';
import {useLocalStorage} from '@vueuse/core';

export function useIndicatorItems() {
    const defaultItems = [
        {name: 'EMA'},
        {name: 'SMA'},
        {name: 'VWAP'},
    ];

    const defaultActive = '5m';

    const storage = useLocalStorage('CandlestickChart.Indicators', {
        favorites: ['EMA', 'SMA', 'VWAP',],
        active: defaultActive,
    });

    const indicatorItems = ref(
        defaultItems.map((item) => ({
            ...item,
            label: item.name,
            favorite: storage.value.favorites.includes(item.name),
        }))
    );

    watch(indicatorItems, (newItems) => {
        storage.value.favorites = newItems
            .filter((item) => item.favorite)
            .map((item) => item.name);
    }, {deep: true});

    const activeIndicator = computed({
        get: () => {
            let active = indicatorItems.value.find((item) => item.name === storage.value.active);

            if (active && !active.favorite && !active.temp) {
                active = activeIndicator.value = indicatorItems.value.find((item) => item.name === defaultActive);
            }

            return active;
        },
        set: (newItem) => {
            storage.value.active = newItem?.name || defaultActive;
        },
    });

    return {indicatorItems, activeIndicator};
}
