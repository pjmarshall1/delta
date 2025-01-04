import {defineStore} from "pinia";
import {useSessionStorage} from "@vueuse/core"

export let useScanFilterStore = defineStore('scanFilterStore', {
    state() {
        return {
            filters: useSessionStorage('ScanFilters', {
                endDate: null, startDate: null,
            }),
        }
    },
    getters: {
        queryParams() {
            let params = {};

            Object.entries(this.filters).forEach(([key, value]) => {
                if (value !== null) params[key] = value;
            });

            return params;
        }
    },
});
