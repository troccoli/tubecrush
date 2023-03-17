describe('The footer', function () {
    it('should show valid links', function () {
        cy.visit({route: 'home'});

        cy.getCy('about-us-link')
            .should('include.text', 'About us')
            .click()
            .assertRoute('about-us');

        cy.getCy('guidelines-link')
            .should('include.text', 'Photo guidelines')
            .click()
            .assertRoute('guidelines');

        cy.getCy('legal-link')
            .should('include.text', 'Legal')
            .click()
            .assertRoute('legal');

        cy.getCy('contact-us-link')
            .should('include.text', 'Contact us')
            .click()
            .assertRoute('contact-us');

        cy.getCy('photo-removal-link')
            .should('include.text', 'Photo removal')
            .click()
            .assertRoute('photo-removal');

        cy.getCy('press-enquiries-link')
            .should('include.text', 'Press enquiries')
            .click()
            .assertRoute('press-enquiries');
    });
});
