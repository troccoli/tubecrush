describe('Deleting a post', function () {
    context('for a super admin', () => {
        beforeEach(() => {
            cy.loginAsSuperAdmin();
            cy.visit({route: 'posts.list'});
        })

        it('works', function () {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['now'],
                attributes: {title: 'Testing post for Cypress'}
            });
            cy.reload();
            cy.getCy('pagination-nav')
                .contains('Showing 1 to 5 of 26 results');
            cy.getCy('post').first().within(() => {
                cy.getCy('delete-post-button').click();
            });
            cy.get('#confirm-delete-post-dialog')
                .should('be.visible')
                .within(($el) => {
                    cy.wrap($el).contains('Are you sure you want to delete the following post? Testing post for Cypress');
                    cy.getCy('cancel-delete-post-button')
                        .should('be.visible')
                        .and('contain', 'Never mind');
                    cy.getCy('confirm-delete-post-button')
                        .should('be.visible')
                        .and('contain', 'Yes please');
                });
            cy.get('#confirm-delete-post-dialog').within(() => {
                cy.getCy('cancel-delete-post-button').click();
            })
            cy.get('#confirm-delete-post-dialog')
                .should('not.be.visible');

            cy.getCy('post').first().within(() => {
                cy.getCy('delete-post-button').click();
            });
            cy.get('#confirm-delete-post-dialog').within(() => {
                cy.getCy('confirm-delete-post-button').click();
            });

            cy.getCy('pagination-nav')
                .contains('Showing 1 to 5 of 25 results');
        });
    });

    context('for an editor', () => {
        beforeEach(() => {
            cy.loginAsEditor();
            cy.visit({route: 'posts.list'});
        })

        it('is not allowed', function () {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['now'],
                attributes: {title: 'Testing post for Cypress'}
            });
            cy.reload();
            cy.getCy('pagination-nav')
                .contains('Showing 1 to 5 of 26 results');

            cy.getCy('post').first().within(() => {
                cy.getCy('delete-post-button').should('not.exist');
            })
        });
    });
});
