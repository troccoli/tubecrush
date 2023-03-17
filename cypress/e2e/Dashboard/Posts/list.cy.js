describe('The list of posts', function () {
    context('for a super admin', () => {
        beforeEach(() => {
            cy.loginAsSuperAdmin();
            cy.visit({route: 'posts.list'});
        })

        it('has a button to add a new post', function () {
            cy.getCy('create-post-button')
                .should('be.visible')
                .and('have.attr', 'title', 'Create post')
                .click();
            cy.assertRoute('posts.create');
        });

        it('is paginated', function () {
            cy.getCy('posts-list')
                .within(() => {
                    cy.getCy('post')
                        .should('have.length', 5);
                });

            cy.getCy('pagination-nav')
                .contains('Showing 1 to 5 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('be.visible');
                    nav.getCy('previous-page-button').should('not.exist');
                    nav.getCy('page-1-span').should('be.visible');
                    nav.getCy('page-1-button').should('not.exist');
                    nav.getCy('page-2-span').should('not.exist');
                    nav.getCy('page-2-button').should('be.visible');
                    nav.getCy('page-3-span').should('not.exist');
                    nav.getCy('page-3-button').should('be.visible');
                    nav.getCy('page-4-span').should('not.exist');
                    nav.getCy('page-4-button').should('be.visible');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });

            // Go to the next
            cy.getCy('next-page-button').filter(':visible').click();
            cy.getCy('pagination-nav')
                .contains('Showing 6 to 10 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('not.exist');
                    nav.getCy('previous-page-button').should('be.visible');
                    nav.getCy('page-1-span').should('not.exist');
                    nav.getCy('page-1-button').should('be.visible');
                    nav.getCy('page-2-span').should('be.visible');
                    nav.getCy('page-2-button').should('not.exist');
                    nav.getCy('page-3-span').should('not.exist');
                    nav.getCy('page-3-button').should('be.visible');
                    nav.getCy('page-4-span').should('not.exist');
                    nav.getCy('page-4-button').should('be.visible');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });

            // Go to a specific page
            cy.getCy('page-4-button').click();
            cy.getCy('pagination-nav')
                .contains('Showing 16 to 20 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('not.exist');
                    nav.getCy('previous-page-button').should('be.visible');
                    nav.getCy('page-1-span').should('not.exist');
                    nav.getCy('page-1-button').should('be.visible');
                    nav.getCy('page-2-span').should('not.exist');
                    nav.getCy('page-2-button').should('be.visible');
                    nav.getCy('page-3-span').should('not.exist');
                    nav.getCy('page-3-button').should('be.visible');
                    nav.getCy('page-4-span').should('be.visible');
                    nav.getCy('page-4-button').should('not.exist');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });

            // Go to the previous page
            cy.getCy('previous-page-button').filter(':visible').click();
            cy.getCy('pagination-nav')
                .contains('Showing 11 to 15 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('not.exist');
                    nav.getCy('previous-page-button').should('be.visible');
                    nav.getCy('page-1-span').should('not.exist');
                    nav.getCy('page-1-button').should('be.visible');
                    nav.getCy('page-2-span').should('not.exist');
                    nav.getCy('page-2-button').should('be.visible');
                    nav.getCy('page-3-span').should('be.visible');
                    nav.getCy('page-3-button').should('not.exist');
                    nav.getCy('page-4-span').should('not.exist');
                    nav.getCy('page-4-button').should('be.visible');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });
        });

        it('shows 5 posts', function () {
            cy.getCy('posts-list')
                .within(() => {
                    cy.getCy('post')
                        .should('have.length', 5)
                        .each(($el, index, $list) => {
                            let post = cy.wrap($el);

                            post.getCy('post-title').should('be.visible');
                            post.getCy('post-author').should('be.visible');
                            post.getCy('post-creation-date').should('be.visible');
                            post.getCy('post-publication-date').should('be.visible');
                            post.getCy('edit-post-button').should('be.visible');
                            post.getCy('delete-post-button').should('be.visible');
                            post.getCy('publish-post-button').should('not.exist');
                            post.getCy('unpublish-post-button').should('be.visible');
                        });
                })
        });

        it('shows the publish button for a draft post', function () {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['now', 'draft']
            });
            cy.reload();
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

        it('allows editing a post', function () {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['now']
            }).then(post => {
                cy.reload();
                cy.getCy('post').first().within(() => {
                    cy.getCy('edit-post-button').click();
                });
                cy.assertRoute('posts.update', {postId: post.id});
            });
        });
    });

    context('for an editor', () => {
        beforeEach(() => {
            cy.loginAsEditor();
            cy.visit({route: 'posts.list'});
        })

        it('has a button to add a new post', function () {
            cy.getCy('create-post-button')
                .should('be.visible')
                .and('have.attr', 'title', 'Create post')
                .click();
            cy.assertRoute('posts.create');
        });

        it('is paginated', function () {
            cy.getCy('posts-list')
                .within(() => {
                    cy.getCy('post')
                        .should('have.length', 5);
                });

            cy.getCy('pagination-nav')
                .contains('Showing 1 to 5 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('be.visible');
                    nav.getCy('previous-page-button').should('not.exist');
                    nav.getCy('page-1-span').should('be.visible');
                    nav.getCy('page-1-button').should('not.exist');
                    nav.getCy('page-2-span').should('not.exist');
                    nav.getCy('page-2-button').should('be.visible');
                    nav.getCy('page-3-span').should('not.exist');
                    nav.getCy('page-3-button').should('be.visible');
                    nav.getCy('page-4-span').should('not.exist');
                    nav.getCy('page-4-button').should('be.visible');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });

            // Go to the next
            cy.getCy('next-page-button').filter(':visible').click();
            cy.getCy('pagination-nav')
                .contains('Showing 6 to 10 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('not.exist');
                    nav.getCy('previous-page-button').should('be.visible');
                    nav.getCy('page-1-span').should('not.exist');
                    nav.getCy('page-1-button').should('be.visible');
                    nav.getCy('page-2-span').should('be.visible');
                    nav.getCy('page-2-button').should('not.exist');
                    nav.getCy('page-3-span').should('not.exist');
                    nav.getCy('page-3-button').should('be.visible');
                    nav.getCy('page-4-span').should('not.exist');
                    nav.getCy('page-4-button').should('be.visible');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });

            // Go to a specific page
            cy.getCy('page-4-button').click();
            cy.getCy('pagination-nav')
                .contains('Showing 16 to 20 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('not.exist');
                    nav.getCy('previous-page-button').should('be.visible');
                    nav.getCy('page-1-span').should('not.exist');
                    nav.getCy('page-1-button').should('be.visible');
                    nav.getCy('page-2-span').should('not.exist');
                    nav.getCy('page-2-button').should('be.visible');
                    nav.getCy('page-3-span').should('not.exist');
                    nav.getCy('page-3-button').should('be.visible');
                    nav.getCy('page-4-span').should('be.visible');
                    nav.getCy('page-4-button').should('not.exist');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });

            // Go to the previous page
            cy.getCy('previous-page-button').filter(':visible').click();
            cy.getCy('pagination-nav')
                .contains('Showing 11 to 15 of 25 results');
            cy.getCy('pagination-nav')
                .within(($el) => {
                    let nav = cy.wrap($el);
                    nav.getCy('previous-page-span').should('not.exist');
                    nav.getCy('previous-page-button').should('be.visible');
                    nav.getCy('page-1-span').should('not.exist');
                    nav.getCy('page-1-button').should('be.visible');
                    nav.getCy('page-2-span').should('not.exist');
                    nav.getCy('page-2-button').should('be.visible');
                    nav.getCy('page-3-span').should('be.visible');
                    nav.getCy('page-3-button').should('not.exist');
                    nav.getCy('page-4-span').should('not.exist');
                    nav.getCy('page-4-button').should('be.visible');
                    nav.getCy('page-5-span').should('not.exist');
                    nav.getCy('page-5-button').should('be.visible');
                    nav.getCy('next-page-span').should('not.exist');
                    nav.getCy('next-page-button').should('be.visible');
                });
        });

        it('shows 5 posts', function () {
            cy.getCy('posts-list')
                .within(() => {
                    cy.getCy('post')
                        .should('have.length', 5)
                        .each(($el, index, $list) => {
                            let post = cy.wrap($el);

                            post.getCy('post-title').should('be.visible');
                            post.getCy('post-author').should('be.visible');
                            post.getCy('post-creation-date').should('be.visible');
                            post.getCy('post-publication-date').should('be.visible');
                            post.getCy('edit-post-button').should('be.visible');
                            post.getCy('delete-post-button').should('not.exist');
                            post.getCy('publish-post-button').should('not.exist');
                            post.getCy('unpublish-post-button').should('be.visible');
                        });
                })
        });

        it('shows the publish button for a draft post', function () {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['now', 'draft']
            });
            cy.reload();
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

        it('allows editing a post', function () {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['now']
            }).then(post => {
                cy.reload();
                cy.getCy('post').first().within(() => {
                    cy.getCy('edit-post-button').click();
                });
                cy.assertRoute('posts.update', {postId: post.id});
            });
        });
    });
});
