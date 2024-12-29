/**
 * Calculate the EMA (Exponential Moving Average) for a dataset.
 * @param {Array} data - Array of candlestick data.
 * @param {number} length - The length for the EMA calculation.
 * @returns {Array} Array of objects with `time` and EMA `value`.
 */
export function calculateEMA(data, length) {
    if (!data.length || length > data.length) return [];

    const newData = [];
    const multiplier = 2 / (length + 1);
    let sum = 0;
    let cv = 0;

    data.forEach((item, index) => {
        if (index < length) {
            sum += item.close;
        } else if (index === length) {
            cv = sum / length;
            newData.push({time: item.time, value: parseFloat(cv.toFixed(2))});
        } else {
            cv = (item.close * multiplier) + (cv * (1 - multiplier));
            newData.push({time: item.time, value: cv});
        }
    });

    return newData;
}
