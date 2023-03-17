describe('Registering a user', function () {
    beforeEach(() => {
        cy.loginAsSuperAdmin();
        cy.visit({route: 'register'});
    })

    it('works', function () {
        cy.get('input#name').parent().contains('label', 'Name');
        cy.get('input#email').parent().contains('label', 'Email');
        cy.contains('button[type="submit"]', 'Register');

        // Name and email are mandatory
        cy.get('button[type="submit"]').click();
        cy.get('input#name').parent().contains('The name field is required.');
        cy.get('input#email').parent().contains('The email field is required.');
        cy.contains('The user has been registered.').should('not.be.visible');

        // Name must not be longer than 255 characters
        cy.get('input#name').type(cy.tubecrush.generateRandomString(256));
        cy.get('button[type="submit"]').click();
        cy.get('input#name').parent().contains('The name field must not be greater than 255 characters.');
        cy.contains('The user has been registered.').should('not.be.visible');

        // The email must not have been used before
        cy.get('input#email').type('editor@example.com');
        cy.get('button[type="submit"]').click();
        cy.get('input#email').parent().contains('The email has already been taken.');
        cy.contains('The user has been registered.').should('not.be.visible');

        // Green journey
        cy.get('input#name').type('{selectAll}{backspace}John');
        cy.get('input#email').type('{selectAll}{backspace}john@example.com');
        cy.get('button[type="submit"]').click();
        cy.contains('The user has been registered.').should('be.visible');
        cy.wait(5);
        cy.contains('The user has been registered.').should('not.be.visible');
    });
});
