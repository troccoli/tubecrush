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

                createDbBackup() {
                    if (fs.existsSync('database/database.sqlite')) {
                        fs.copyFileSync('database/database.sqlite', 'database/database.backup.sqlite');
                    }

                    return null;
                },

                restoreDbBackup() {
                    if (fs.existsSync('database/database.backup.sqlite')) {
                        fs.copyFileSync('database/database.backup.sqlite', 'database/database.sqlite');
                    }

                    return null;
                },

                removeDbBackup() {
                    if (fs.existsSync('database/database.backup.sqlite')) {
                        fs.rmSync('database/database.backup.sqlite');
                    }

                    return null;
                }
            })
        },
    },
});
