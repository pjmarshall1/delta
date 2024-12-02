import numeral from "numeral";
import dayjs from "dayjs";

declare global {
    const numeral: typeof numeral;
    const dayjs: typeof dayjs;
}

export {};
