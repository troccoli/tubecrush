Cypress.Laravel = {
    routes: {},

    route: (name, parameters = {}, options = {}) => {
        assert(
            Cypress.Laravel.routes.hasOwnProperty(name),
            `Laravel route "${name}" does not exist.`
        );

        let uri = ((uri) => {
            Object.keys(parameters).forEach((parameter) => {
                uri = uri.replace(
                    new RegExp(`{${parameter}}`),
                    parameters[parameter]
                );
            });

            return uri;
        })(Cypress.Laravel.routes[name].uri);

        if (options.hasOwnProperty('fullUrl') && options.fullUrl === true) {
            uri = Cypress.config().baseUrl + '/' + uri;
        }

        return uri;
    }
};
