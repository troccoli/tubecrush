describe('The footer', function () {
    it('should show valid links', function () {
        cy.visit({route: 'home'});

        cy.getCy('about-us-link')
            .should('include.text', 'About us')
            .click()
            .url().should('eq', Cypress.Laravel.route('about-us', {}, {fullUrl: true}))

        cy.getCy('guidelines-link')
            .should('include.text', 'Photo guidelines')
            .click()
            .url().should('eq', Cypress.Laravel.route('guidelines', {}, {fullUrl: true}))

        cy.getCy('legal-link')
            .should('include.text', 'Legal')
            .click()
            .url().should('eq', Cypress.Laravel.route('legal', {}, {fullUrl: true}))

        cy.getCy('contact-us-link')
            .should('include.text', 'Contact us')
            .click()
            .url().should('eq', Cypress.Laravel.route('contact-us', {}, {fullUrl: true}))

        cy.getCy('photo-removal-link')
            .should('include.text', 'Photo removal')
            .click()
            .url().should('eq', Cypress.Laravel.route('photo-removal', {}, {fullUrl: true}))

        cy.getCy('press-enquiries-link')
            .should('include.text', 'Press enquiries')
            .click()
            .url().should('eq', Cypress.Laravel.route('press-enquiries', {}, {fullUrl: true}))
    });
});
