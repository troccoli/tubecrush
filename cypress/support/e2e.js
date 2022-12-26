// ***********************************************************
// This example support/e2e.js is processed and
// loaded automatically before your test files.
//
// This is a great place to put global configuration and
// behavior that modifies Cypress.
//
// You can change the location of this file or turn off
// automatically serving support files with the
// 'supportFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/configuration
// ***********************************************************

// Import commands.js using ES2015 syntax:
import './commands'
import './laravel-commands';
import './laravel-routes';
import './assertions';
import './functions';
// Alternatively you can use CommonJS syntax:
// require('./commands')
require('cypress-terminal-report/src/installLogsCollector')();

before(() => {
    cy.task('activateCypressEnvFile', {}, {log: false});
    cy.artisan('config:cache', {}, {log: false});

    cy.refreshRoutes();
    cy.refreshDatabase({'--seed': true});
    cy.task('createDbBackup', {}, {log: true});

    cy.intercept('/livewire/**/*').as('forLivewire');

    Cypress.on('uncaught:exception', (err, runnable) => {
        // returning false here prevents Cypress from
        // failing the test
        return false
    });
});

beforeEach(() => {
    cy.task('restoreDbBackup', {}, {log: true});
    cy.acceptCookies();
});

afterEach(() => {
    cy.clearCookies();
});

after(() => {
    cy.task('removeDbBackup', {}, {log: true});
    cy.task('activateLocalEnvFile', {}, {log: false});
    cy.artisan('config:clear', {}, {log: false});
});
