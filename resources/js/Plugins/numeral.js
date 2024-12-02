import numeral from "numeral";

export default {
    install(app) {
        // Add $numeral as a global property
        app.config.globalProperties.numeral = numeral;
    },
};
