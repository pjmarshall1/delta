import {SessionHighlighting} from "@/Components/CandlestickChart/Plugins/session-highlighting.ts";

export function useSessionHighlighter() {
    const sessionHighlighter = (time) => {
        const date = dayjs.utc(time * 1000).format('YYYY-MM-DD');

        if (dayjs.utc(time * 1000).isBetween(dayjs.utc(`${date} 03:59:00`), dayjs.utc(`${date} 09:30:00`)) ||
            dayjs.utc(time * 1000).isBetween(dayjs.utc(`${date} 15:59:00`), dayjs.utc(`${date} 20:00:00`))) {
            return 'rgb(241 245 249)'; // Default color
        }
    };

    const sessionHighlighting = new SessionHighlighting(sessionHighlighter);

    return {
        sessionHighlighting
    };
}
