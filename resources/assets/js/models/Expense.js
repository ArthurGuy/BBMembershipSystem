
var Backbone = require('backbone');

var Expense = Backbone.Model.extend({

    defaults: {
        user_id: null,
        category: null,
        description: null,
        amount: null,
        approved: false,
        declined: false
    },

    approve: function () {
        this.save({
            approved: true
        });
    }
});

export default Expense;