
var Backbone = require('backbone');

var Expense = Backbone.Model.extend({

    url: '/expenses',

    defaults: {
        user_id: null,
        category: null,
        description: null,
        amount: null,
        approved: false,
        declined: false,
        expense_date: null
    },

    fileAttribute: 'file',

    approve: function () {
        this.save({
            approved: true
        });
    }
});

export default Expense;