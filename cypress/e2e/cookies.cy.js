describe('Cookies consent', function () {
    beforeEach(() => {
        cy.clearCookies();
    })

    it('should show a pop-up to consent or reject cookies', function () {
        cy.getCookie('tubecrush_cookie_consent').should('be.null');
        cy.visit({route: 'home'});

        cy.getCy('cookie-consent-modal').should('be.visible');

        cy.getCy('cookie-consent-refuse-button')
            .should('be.visible')
            .and('contain', 'Refuse cookies');

        cy.getCy('cookie-consent-accept-button')
            .should('be.visible')
            .and('contain', 'Accept cookies');

        cy.getCy('cookie-consent-cookie-policy-button').click();
        cy.getCy('cookie-consent-modal')
            .should('not.be.visible');
        cy.getCy('cookie-policy-modal')
            .should('be.visible')
            .and('contain', 'Cookie Statement');

        cy.getCy('cookie-policy-close-button')
            .should('be.visible')
            .and('contain', 'Close')

        cy.getCy('cookie-policy-close-button').click();
        cy.getCy('cookie-consent-modal')
            .should('not.visible');
        cy.getCy('cookie-policy-modal')
            .should('not.be.visible');
    });

    it('should accept cookies', function () {
        cy.getCookie('tubecrush_cookie_consent').should('be.null');
        cy.visit({route: 'home'});

        cy.getCy('cookie-consent-modal').should('be.visible');

        cy.getCy('cookie-consent-accept-button').click();

        cy.getCy('cookie-consent-modal').should('not.exist');

        cy.getCookie('tubecrush_cookie_consent').should('not.be.null');
        cy.visit({route: 'home'});

        cy.getCy('cookie-consent-modal').should('not.exist');
    });

    it('should refuse cookies', function () {
        cy.getCookie('tubecrush_cookie_consent').should('be.null');
        cy.visit({route: 'home'});

        cy.getCy('cookie-consent-modal').should('be.visible');

        cy.getCy('cookie-consent-refuse-button').click();

        cy.getCy('cookie-consent-modal').should('not.exist');
        cy.getCookie('tubecrush_cookie_consent').should('not.be.null');

        cy.visit({route: 'home'});

        cy.getCy('cookie-consent-modal').should('not.exist');
    });
});
