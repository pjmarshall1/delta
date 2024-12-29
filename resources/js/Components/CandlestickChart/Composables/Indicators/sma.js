/**
 * Calculate the SMA (Simple Moving Average) for a dataset.
 * @param {Array} data - Array of candlestick data.
 * @param {number} length - The length for the SMA calculation.
 * @returns {Array} Array of objects with `time` and SMA `value`.
 */
export function calculateSMA(data, length) {
    if (!data.length || length > data.length) return [];

    const newData = [];
    let sum = 0;

    data.forEach((item, index) => {
        sum += item.close;

        if (index >= length - 1) {
            if (index >= length) {
                sum -= data[index - length].close;
            }

            newData.push({
                time: item.time,
                value: sum / length,
            });
        }
    });

    return newData;
}
