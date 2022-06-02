describe('Homepage', function () {
    it('should display the banner', function () {
        cy.visit('/')
            .get('#banner').should('be.visible');
    });
});
