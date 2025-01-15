import {defineStore} from "pinia";
import {useSessionStorage} from "@vueuse/core"

export let useScanFilterStore = defineStore('scanFilterStore', {
    state() {
        return {
            filters: useSessionStorage('scan.filters', {
                direction: null,
                endDate: null,
                float: null,
                session: null,
                reviewed: null,
                sortBy: null,
                startDate: null,
                symbol: null
            }),
            options: [
                {
                    key: 'symbol',
                    name: 'Symbol',
                    autocomplete: true,
                    value: [],
                },
                {
                    key: 'float',
                    name: 'Float',
                    value: [
                        {name: 'Low Float', value: 'low_float'},
                        {name: 'Mid Float', value: 'medium_float'},
                        {name: 'High Float', value: 'high_float'},
                    ],
                },
                {
                    key: 'session',
                    name: 'Session',
                    value: [
                        {name: 'Premarket', value: 'pre_market'},
                        {name: 'Open Market', value: 'open_market'},
                        {name: 'Aftermarket', value: 'after_market'},
                    ],
                },
                {
                    key: 'reviewed',
                    name: 'Reviewed / Not Reviewed',
                    value: [
                        {name: 'Reviewed', value: 'true'},
                        {name: 'Not Reviewed', value: 'false'},
                    ],
                },
            ],
        }
    },
    actions: {
        setAdvancedFilters(value) {
            const {direction, endDate, sortBy, startDate, ...rest} = this.filters;
            Object.keys(rest).forEach(key => this.filters[key] = value[key]);
        },
        resetAdvancedFilters() {
            const {direction, endDate, sortBy, startDate, ...rest} = this.filters;
            Object.keys(rest).forEach(key => this.filters[key] = null);
        },
        setDateRange(dateRange) {
            this.filters.startDate = dateRange.startDate;
            this.filters.endDate = dateRange.endDate;
        },
        resetDateRange() {
            this.filters.startDate = null;
            this.filters.endDate = null;
        },
        setSorting(sorting) {
            this.filters.direction = sorting.direction;
            this.filters.sortBy = sorting.field;
        },
    },
    getters: {
        advancedFilters() {
            const {direction, endDate, sortBy, startDate, ...rest} = this.filters;
            return rest;
        },
        dateRange() {
            return {
                startDate: this.filters.startDate,
                endDate: this.filters.endDate
            }
        },
        sorting() {
            return {
                direction: this.filters.direction,
                field: this.filters.sortBy
            }
        },
        queryParams() {
            let params = {};

            Object.entries(this.filters).forEach(([key, value]) => {
                if (value !== null) params[key] = value;
            });

            return params;
        }
    },
});
