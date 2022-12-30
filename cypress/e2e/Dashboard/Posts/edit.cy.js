describe('Editing a post', function () {
    context('for a super admin', () => {
        beforeEach(() => {
            cy.loginAsSuperAdmin();

            cy.create({
                model: 'App\\Models\\Post',
                state: ['now', 'withTags'],
                load: ['line', 'tags']
            }).as('postToEdit');
            cy.get('@postToEdit').then(post => {
                cy.visit({route: 'posts.update', parameters: {postId: post.id}})
            });
        })

        it('show the correct fields and buttons', function () {
            cy.get('@postToEdit').then(postToEdit => {
                cy.get('#title')
                    .should('have.value', postToEdit.title)
                    .parent()
                    .within($el => {
                        cy.wrap($el).get('label').should('contain', 'Title');
                        cy.wrap($el).get('#slug').should('contain', 'Slug: ' + postToEdit.slug)
                    });
                cy.getCy('line-select')
                    .should('contain', postToEdit.line.name)
                    .parent()
                    .find('label').should('contain', 'Line');
                cy.get('#content')
                    .should('have.value', postToEdit.content)
                    .parent()
                    .find('label').should('contain', 'Content');
                cy.getCy('upload-photo-button')
                    .should('be.visible')
                    .and('contain', 'Upload a photo');
                cy.getCy('photo-image')
                    .should('have.attr', 'src', '/storage/' + postToEdit.photo);
                cy.get('#photo-credit')
                    .should('have.value', postToEdit.photo_credit)
                    .parent()
                    .find('label').should('contain', 'Photo submitted by');
                cy.getCy('tags-select')
                    .then($el => {
                        let tags = cy.wrap($el).find('ul');
                        postToEdit.tags.forEach(tag => tags.should('contain', tag.name));
                    });
                cy.getCy('tags-select')
                    .parent()
                    .find('label').should('contain', 'Tags');

                cy.getCy('cancel-button')
                    .should('be.visible')
                    .and('contain', 'Cancel');
                cy.getCy('submit-button')
                    .should('be.visible')
                    .and('contain', 'Update');
            });
        });
    });

    context('for an editor', () => {
        beforeEach(() => {
            cy.loginAsEditor();

            cy.create({
                model: 'App\\Models\\Post',
                state: ['now', 'withTags'],
                load: ['line', 'tags']
            }).as('postToEdit');
            cy.get('@postToEdit').then(post => {
                cy.visit({route: 'posts.update', parameters: {postId: post.id}})
            });
        });

        it('show the correct fields and buttons', function () {
            cy.get('@postToEdit').then(postToEdit => {
                cy.get('#title')
                    .should('have.value', postToEdit.title)
                    .parent()
                    .within($el => {
                        cy.wrap($el).get('label').should('contain', 'Title');
                        cy.wrap($el).get('#slug').should('contain', 'Slug: ' + postToEdit.slug)
                    });
                cy.getCy('line-select')
                    .should('contain', postToEdit.line.name)
                    .parent()
                    .find('label').should('contain', 'Line');
                cy.get('#content')
                    .should('have.value', postToEdit.content)
                    .parent()
                    .find('label').should('contain', 'Content');
                cy.getCy('upload-photo-button')
                    .should('be.visible')
                    .and('contain', 'Upload a photo');
                cy.getCy('photo-image')
                    .should('have.attr', 'src', '/storage/' + postToEdit.photo);
                cy.get('#photo-credit')
                    .should('have.value', postToEdit.photo_credit)
                    .parent()
                    .find('label').should('contain', 'Photo submitted by');
                cy.getCy('tags-select')
                    .then($el => {
                        let tags = cy.wrap($el).find('ul');
                        postToEdit.tags.forEach(tag => tags.should('contain', tag.name));
                    });
                cy.getCy('tags-select')
                    .parent()
                    .find('label').should('contain', 'Tags');

                cy.getCy('cancel-button')
                    .should('be.visible')
                    .and('contain', 'Cancel');
                cy.getCy('submit-button')
                    .should('be.visible')
                    .and('contain', 'Update');
            });
        });
    });
});
