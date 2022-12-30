describe('The dashboard', function () {
    context('for a super admin', () => {
        beforeEach(() => {
            cy.loginAsSuperAdmin();
            cy.visit({route: 'dashboard'});
        });

        it('shows the name of the logged in user', function () {
            cy.contains('header', Cypress.Laravel.currentUser.name + '\'s Dashboard');
        });

        it('contains a button to the list of posts', function () {
            cy.contains('a', 'Posts').click();
            cy.assertRoute('posts.list');
        });

        it('contains a button to register a user', function () {
            cy.contains('a', 'Users').click();
            cy.assertRoute('register');
        });
    });

    context('for an editor', () => {
        beforeEach(() => {
            cy.loginAsEditor();
            cy.visit({route: 'dashboard'});
        });

        it('shows the name of the logged in user', function () {
            cy.contains('header', Cypress.Laravel.currentUser.name + '\'s Dashboard');
        });

        it('contains a button to the list of posts', function () {
            cy.contains('a', 'Posts').click();
            cy.assertRoute('posts.list');
        });

        it('does not contain a button to register a user', function () {
            cy.contains('a', 'Users').should('not.exist');
        });
    })
});
