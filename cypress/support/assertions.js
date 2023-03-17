Cypress.Commands.add('assertRedirect', path => {
    cy.location('pathname').should('eq', `/${path}`.replace(/^\/\//, '/'));
});

Cypress.Commands.add('assertRoute', (route, parameters) => {
    let url = Cypress.Laravel.route(route, parameters || {});
    cy.location('pathname').should('eq', `/${url}`);
})
