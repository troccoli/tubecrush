describe('Unpublishing a post', function () {
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
            cy.getCy('post')
                .first()
                .within(() => {
                    cy.getCy('post-publication-date')
                        .should('be.visible')
                        .and('not.contain', 'Draft');
                    cy.getCy('publish-post-button').should('not.exist');
                    cy.getCy('unpublish-post-button').should('be.visible');
                });

            cy.getCy('post').first().within(() => {
                cy.getCy('unpublish-post-button').click();
            });
            cy.get('#confirm-unpublish-post-dialog')
                .should('be.visible')
                .within(($el) => {
                    cy.wrap($el).contains('Are you sure you want to unpublish the following post? Testing post for Cypress');
                    cy.getCy('cancel-unpublish-post-button')
                        .should('be.visible')
                        .and('contain', 'Naah, leave it');
                    cy.getCy('confirm-unpublish-post-button')
                        .should('be.visible')
                        .and('contain', 'Oh yeah');
                });
            cy.get('#confirm-unpublish-post-dialog').within(() => {
                cy.getCy('cancel-unpublish-post-button').click();
            })
            cy.get('#confirm-unpublish-post-dialog')
                .should('not.be.visible');

            cy.getCy('post').first().within(() => {
                cy.getCy('unpublish-post-button').click();
            });
            cy.get('#confirm-unpublish-post-dialog').within(() => {
                cy.getCy('confirm-unpublish-post-button').click();
            });

            cy.getCy('post')
                .first()
                .within(() => {
                    cy.getCy('post-publication-date')
                        .should('be.visible')
                        .and('contain', 'Draft');
                    cy.getCy('publish-post-button').should('be.visible');
                    cy.getCy('unpublish-post-button').should('not.exist');
                });
        });
    });

    context('for an editor', () => {
        beforeEach(() => {
            cy.loginAsEditor();
            cy.visit({route: 'posts.list'});
        })

        it('works', function () {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['now'],
                attributes: {title: 'Testing post for Cypress'}
            });
            cy.reload();
            cy.getCy('post')
                .first()
                .within(() => {
                    cy.getCy('post-publication-date')
                        .should('be.visible')
                        .and('not.contain', 'Draft');
                    cy.getCy('publish-post-button').should('not.exist');
                    cy.getCy('unpublish-post-button').should('be.visible');
                });

            cy.getCy('post').first().within(() => {
                cy.getCy('unpublish-post-button').click();
            });
            cy.get('#confirm-unpublish-post-dialog')
                .should('be.visible')
                .within(($el) => {
                    cy.wrap($el).contains('Are you sure you want to unpublish the following post? Testing post for Cypress');
                    cy.getCy('cancel-unpublish-post-button')
                        .should('be.visible')
                        .and('contain', 'Naah, leave it');
                    cy.getCy('confirm-unpublish-post-button')
                        .should('be.visible')
                        .and('contain', 'Oh yeah');
                });
            cy.get('#confirm-unpublish-post-dialog').within(() => {
                cy.getCy('cancel-unpublish-post-button').click();
            })
            cy.get('#confirm-unpublish-post-dialog')
                .should('not.be.visible');

            cy.getCy('post').first().within(() => {
                cy.getCy('unpublish-post-button').click();
            });
            cy.get('#confirm-unpublish-post-dialog').within(() => {
                cy.getCy('confirm-unpublish-post-button').click();
            });

            cy.getCy('post')
                .first()
                .within(() => {
                    cy.getCy('post-publication-date')
                        .should('be.visible')
                        .and('contain', 'Draft');
                    cy.getCy('publish-post-button').should('be.visible');
                    cy.getCy('unpublish-post-button').should('not.exist');
                });
        });
    });
});
