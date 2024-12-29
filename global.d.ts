declare global {
    const numeral: typeof numeral;
    const dayjs: typeof dayjs;
    const currency: (value: number) => string;
}

export {};
