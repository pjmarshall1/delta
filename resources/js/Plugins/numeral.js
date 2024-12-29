import numeral from "numeral";

export default {
    install(app) {
        // Add $numeral as a global property
        app.config.globalProperties.numeral = numeral;
        app.config.globalProperties.currency = (value) => numeral(value).format('0.00[00]')
    },
};
