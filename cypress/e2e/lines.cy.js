import dayjs from "dayjs";

describe('The Post by Lines page', function () {
    beforeEach(() => {
        cy.create({
            model: 'App\\Models\\Line',
            attributes: {name: 'New TubeCrush Line', slug: 'new-tubecrush-line'}
        }).as('newLine');
        cy.get('@newLine').then(line => {
            cy.create({
                model: 'App\\Models\\Post',
                attributes: {line_id: line.id},
                count: 10,
            });

            cy.create({
                model: 'App\\Models\\Post',
                state: ['publishedNow', 'withTags'],
                load: ['tags'],
                attributes: {title: 'Latest Post', line_id: line.id},
            }).as('latestPost');
        });

        cy.visit({route: 'home'});
        cy.getCy('post').first().within(() => {
            cy.getCy('line').click();
        });
    })

    it('should show a list of posts', function () {
        cy.getCy('post').should('have.length', 3);
        cy.getCy('more-posts-button')
            .should('include.text', 'More posts')
            .click()
            .getCy('post').should('have.length', 6);
    });

    it('should not show draft posts', function () {
        cy.get('@newLine').then(line => {
            cy.create({
                model: 'App\\Models\\Post',
                state: ['draft'],
                attributes: {title: 'Draft post', line_id: line.id},
            })
        });

        cy.visit({route: 'home'});
        cy.getCy('post').first().within(() => {
            cy.getCy('title').should('not.contain', 'Draft post');
        });
    });

    it.skip('should show the correct information for a post', function () {
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

    it('should go to the tag page when clicking on a tag for a post', function () {
        cy.getCy('post').first().within(() => {
            cy.getCy('tags').find(':first').click();
        });
        cy.get('@latestPost').then((post) => {
            cy.assertRoute('posts-by-tags', {slug: post.tags[0].slug});
        });
    });

    it('should go to the single post page when clicking on the title of a post', function () {
        cy.getCy('post').first().within((post) => {
            cy.getCy('title').click();
        });

        cy.get('@latestPost').then((post) => {
            cy.assertRoute('single-post', {post: post.slug});
        })
    });
});
