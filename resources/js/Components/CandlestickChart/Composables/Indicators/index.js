import {calculateVWAP} from './VWAP';
import {calculateEMA} from './EMA';
import {calculateSMA} from './SMA';

export const indicators = {
    VWAP: calculateVWAP,
    EMA: calculateEMA,
    SMA: calculateSMA,
};
