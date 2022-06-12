const {defineConfig} = require("cypress");
const fs = require("fs");
const dayjs = require("dayjs")

module.exports = defineConfig({
    e2e: {
        baseUrl: 'http://localhost',
        chromeWebSecurity: false,
        defaultCommandTimeout: 5000,
        setupNodeEvents(on, config) {
            require('cypress-terminal-report/src/installLogsPrinter')(on, {printLogsToConsole: 'always'});
            on('task', {
                activateCypressEnvFile() {
                    if (fs.existsSync('.env.cypress')) {
                        fs.renameSync('.env', '.env.backup');
                        fs.renameSync('.env.cypress', '.env');
                    }

                    return null;
                },

                activateLocalEnvFile() {
                    if (fs.existsSync('.env.backup')) {
                        fs.renameSync('.env', '.env.cypress');
                        fs.renameSync('.env.backup', '.env');
                    }

                    return null;
                },
            })
        },
    },
});
