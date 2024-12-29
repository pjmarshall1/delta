import dayjs from 'dayjs';

/**
 * Calculate the VWAP (Volume Weighted Average Price) for a dataset.
 * @param {Array} data - Array of candlestick data.
 * @returns {Array} Array of objects with `time` and VWAP `value`.
 */
export function calculateVWAP(data) {
    if (!data.length) return [];
    const newData = [];
    let cv = 0;
    let cp = 0;
    let pt = data[0].time;

    data.forEach(item => {
        const datetime1 = dayjs.unix(pt);
        const datetime2 = dayjs.unix(item.time);

        if (!datetime1.isSame(datetime2, 'day')) {
            pt = item.time;
            cp = 0;
            cv = 0;
        }

        cp += ((item.high + item.low + item.close) / 3) * item.volume;
        cv += item.volume;

        newData.push({
            time: item.time,
            value: cv ? cp / cv : 0
        });
    });

    return newData;
}
