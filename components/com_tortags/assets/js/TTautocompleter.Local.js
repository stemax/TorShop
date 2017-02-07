/**
 * Autocompleter.Local
 *
 * http://digitarald.de/project/autocompleter/
 *
 * @version        1.1.2
 *
 * @license        MIT-style license
 * @author        Harald Kirschner <mail [at] digitarald.de>
 * @copyright    Author
 */

TTautocompleter.Local = new Class({
    Extends: TTautocompleter,
    options: {
        minLength: 0,
        delay: 200
    },
    initialize: function (element, tokens, options) {
        this.parent(element, options);
        this.tokens = tokens;
    },
    query: function () {
        this.update(this.filter());
    }

});