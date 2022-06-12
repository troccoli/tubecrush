import dayjs from "dayjs";

describe('The homepage', function () {
    it('should show a list of posts', function () {
        cy.visit({route: 'home'});
        cy.getCy('post').should('have.length', 3);
        cy.getCy('more-posts-button')
            .should('include.text', 'More posts')
            .click()
            .wait('@forLivewire')
            .getCy('post').should('have.length', 6);
    });

    it('should not show draft posts', function () {
        cy.create({
            model: 'App\\Models\\Post',
            state: ['draft'],
            attributes: {title: 'Draft post'}
        });

        cy.visit({route: 'home'});
        cy.getCy('post').first().within(() => {
            cy.getCy('title').should('not.contain', 'Draft post');
        });
    });

    it('should show the correct information for a post', function () {
        cy.create({
            model: 'App\\Models\\Post',
            attributes: {likes: 3},
            state: ['publishedNow', 'withTags'],
            load: ['line', 'tags']
        })
            .then((post) => {
                let publishedDate = dayjs(post.published_at).format('MMM D, YYYY')

                cy.visit({route: 'home'});
                cy.getCy('post').first().within(() => {
                    cy.getCy('photo-credit').should('contain', post.photo_credit);
                    cy.getCy('line').should('contain', post.line.name);
                    cy.getCy('title').should('contain', post.title);
                    cy.getCy('published-date').should('contain', publishedDate);
                    cy.getCy('content').should('contain', post.content);
                    post.tags.forEach((tag) => {
                        cy.getCy('tags').should('contain', tag.name);
                    })
                    cy.getCy('likes').should('contain', '3 likes')
                    cy.getCy('shares').within(() => {
                        cy.getCy('twitter-share').should('be.visible');
                        cy.getCy('facebook-share').should('be.visible');
                        cy.getCy('copy-link-share').should('be.visible');
                    })
                });
            });
    });

    it('should go to the line page when clicking on the line for a post', function () {
        cy.create({
            model: 'App\\Models\\Post',
            state: ['publishedNow'],
            attributes: {title: 'New post for Cypress testing'}
        });

        cy.visit({route: 'home'});
        cy.getCy('post').first().within(() => {
            cy.getCy('line').click();
        });
        cy.getCy('post').first().within(() => {
            cy.getCy('title').should('contain', 'New post for Cypress testing');
        });
    });

    it('should go to the tag page when clicking on a tag for a post', function () {
        cy.create({
            model: 'App\\Models\\Post',
            state: ['publishedNow', 'withTags'],
            attributes: {title: 'New post for Cypress testing'}
        });

        cy.visit({route: 'home'});
        cy.getCy('post').first().within(() => {
            cy.getCy('tags').first().click();
        });
        cy.getCy('post').first().within(() => {
            cy.getCy('title').should('contain', 'New post for Cypress testing');
        });
    });

    it('should go to the single post page when clicking on the title of a post', function () {
        cy.create({
            model: 'App\\Models\\Post',
            attributes: {title: 'New post for Cypress testing'},
            state: ['publishedNow']
        })
            .then((post) => {
                cy.visit({route: 'home'});
                cy.getCy('post').first().within(() => {
                    cy.getCy('title').click();
                });

                cy.url().should('include', post.slug)
            });
    });

    it('should not show the photo credit if there isn\'t one', function () {
        cy.create({
            model: 'App\\Models\\Post',
            state: ['publishedNow', 'withoutPhotoCredit']
        });

        cy.visit({route: 'home'});
        cy.getCy('post').first().within(() => {
            cy.getCy('photo-credit').should('not.exist');
        });
    });

    it('should display the comment count', function () {
        cy.visit({route: 'home'});
        cy.getCy('post').each(() => {
            cy.getCy('comments-count')
                .should('be.visible')
                .and('contain', '0 Comments');
        })
    });
});
