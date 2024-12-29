import {useLocalStorage} from '@vueuse/core';
import {indicators} from './indicators';
import {v4 as uuidv4} from 'uuid';

export function useIndicators() {
    const activeIndicators = useLocalStorage('Candlestick.Chart.ActiveIndicators', []);

    function calculateIndicator(indicatorConfig, data) {
        const {name, input} = indicatorConfig;

        if (!indicators[name]) {
            throw new Error(`Indicator "${name}" is not defined.`);
        }

        return indicators[name](data, ...(input ? Object.values(input) : []));
    }

    function addActiveIndicator(name, input) {
        const id = uuidv4();
        activeIndicators.value.push({
            id, name, input,
            style: {
                color: '#096dd9',
                lineStyle: 0,
                lineVisible: true,
                lineWidth: 1,
            },
            visibility: {
                minute: {
                    max: 59,
                    min: 1,
                    visible: true,
                },
                hour: {
                    max: 23,
                    min: 1,
                    visible: true,
                },
                day: {
                    max: 365,
                    min: 1,
                    visible: name !== 'VWAP',
                }
            }
        });
        return id;
    }

    function updateActiveIndicator(id, updates) {
        const indicator = activeIndicators.value.find((i) => i.id === id);
        if (indicator) {
            if (updates.input) indicator.input = updates.input;
            if (updates.style) indicator.style = updates.style;
            if (updates.visibility) indicator.visibility = updates.visibility;
        } else {
            throw new Error(`Indicator with ID ${id} not found.`);
        }
    }

    function removeActiveIndicator(id) {
        activeIndicators.value = activeIndicators.value.filter(
            (i) => i.id !== id
        );
    }

    function getActiveIndicator(id) {
        return activeIndicators.value.find((i) => i.id === id) || null;
    }

    return {
        activeIndicators,
        calculateIndicator,
        addActiveIndicator,
        updateActiveIndicator,
        removeActiveIndicator,
        getActiveIndicator,
    };
}
