describe('Login', function () {
    it('redirects to the home page once logged in', function () {
        cy.visit({route: 'login'})
            .get('#email').type('super-admin@example.com')
            .get('#password').type('password')
            .get('[type="submit"]').click()
            .url().should('eq', Cypress.config().baseUrl + '/');
    });
});
