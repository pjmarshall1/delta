import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import isToday from 'dayjs/plugin/isToday';
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore';
import isSamOrAfter from 'dayjs/plugin/isSameOrAfter';
import quarterOfYear from 'dayjs/plugin/quarterOfYear';

dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.extend(isToday);
dayjs.extend(isSameOrBefore);
dayjs.extend(isSamOrAfter);
dayjs.extend(quarterOfYear);

export default {
    install(app) {
        app.config.globalProperties.dayjs = dayjs
    }
}
