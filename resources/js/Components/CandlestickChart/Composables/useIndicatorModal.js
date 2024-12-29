import {reactive} from "vue";

const globalState = reactive({
    indicatorId: null,
    show: false
});

export function useIndicatorModal() {
    return {
        state: (globalState),
        open: (indicatorId) => {
            globalState.indicatorId = indicatorId;
            globalState.show = true;
        },
    }
}
