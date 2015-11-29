var Backbone = require('backbone');

var Expenses = Backbone.Collection.extend({
    // Reference to this collection's model.
    model: require('../models/Expense'),

    url: '/expenses',

    approved: function () {
        return this.where({approved: true});
    },

    unapproved: function () {
        return this.where({approved: false});
    },

    forUser: function (userId) {
        return this.where({user_id: userId});
    }
});

export default Expenses;