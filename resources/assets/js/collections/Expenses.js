var Backbone = require('backbone');

var Expenses = Backbone.Collection.extend({
    // Reference to this collection's model.
    model: require('../models/Expense'),

    url: '/expenses',

    // Filter down the list of all todo items that are finished.
    approved: function () {
        return this.where({approved: true});
    },

    // Filter down the list to only todo items that are still not finished.
    unapproved: function () {
        return this.where({approved: false});
    }
});

export default Expenses;