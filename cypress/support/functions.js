cy.tubecrush = {
    generateRandomString: function (length, randomString = "") {
        randomString += Math.random().toString(36).substr(2, length);
        if (randomString.length > length) return randomString.slice(0, length);
        return cy.tubecrush.generateRandomString(length, randomString);
    }
}
