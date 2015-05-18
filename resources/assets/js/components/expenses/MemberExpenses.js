import React from 'react';

var BackboneMixin = require('../../mixins/Backbone');
var ExpenseItem = require('./ExpenseItem');
var ReactBootstrap = require('react-bootstrap');
var NewExpenseModal = require('./NewExpenseModal');

var MemberExpenses = React.createClass({
    mixins: [BackboneMixin],

    getBackboneCollections: function () {
        return [this.props.expenses];
    },

    getInitialState: function () {
        return {editing: null};
    },

    componentDidMount: function () {
        /*
        var Router = Backbone.Router.extend({
            routes: {
                '': 'all',
                'active': 'active',
                'completed': 'completed'
            },
            all: this.setState.bind(this, {nowShowing: app.ALL_TODOS}),
            active: this.setState.bind(this, {nowShowing: app.ACTIVE_TODOS}),
            completed: this.setState.bind(this, {nowShowing: app.COMPLETED_TODOS})
        });

        new Router();
        Backbone.history.start();
        */

        //Initial load of the expenses
        this.props.expenses.fetch();
    },

    componentDidUpdate: function () {
        //we wont allow editing of expenses, just creation so this may not be needed

        //this triggers a lot of updates, I am not sure why its needed
        //this.props.expenses.forEach(function (expense) {
        //    expense.save();
        //});
    },

    handleNewTodoKeyDown: function (event) {
        if (event.which !== ENTER_KEY) {
            return;
        }

        var val = this.refs.newField.getDOMNode().value.trim();
        if (val) {
            this.props.expenses.create({
                title: val,
                completed: false,
                order: this.props.expenses.nextOrder()
            });
            this.refs.newField.getDOMNode().value = '';
        }

        return false;
    },

    toggleAll: function (event) {
        var checked = event.target.checked;
        this.props.expenses.forEach(function (todo) {
            todo.set('completed', checked);
        });
    },

    edit: function (todo, callback) {
        // refer to todoItem.jsx `handleEdit` for the reason behind the callback
        this.setState({editing: todo.get('id')}, callback);
    },

    save: function (todo, text) {
        todo.save({title: text});
        this.setState({editing: null});
    },

    cancel: function () {
        this.setState({editing: null});
    },

    clearCompleted: function () {
        this.props.expenses.completed().forEach(function (todo) {
            todo.destroy();
        });
    },

    newExpenseFormSubmission: function() {
        console.log('form submitted');
        this.props.expenses.create({description:'saw blades', category:'consumables', amount:980}, {wait: true});
    },

    render: function () {
        var main = <div className="panel-body"><p>Bought an item for Build Brighton, claim the money back here</p></div>;
        var expenses = this.props.expenses;


        var expenseItems = expenses.map(function (expense) {
            return (<ExpenseItem key={expense.get('id')} expense={expense} />);
        }, this);

        if (expenses.length) {
            main = (
                <table className="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {expenseItems}
                    </tbody>
                </table>
            );
        }

        return (
            <div className="panel panel-default">
                <div className="panel-heading">
                    <h3 className="panel-title">Reclaim Expenses</h3>
                    <ReactBootstrap.ModalTrigger modal={<NewExpenseModal onSubmit={x => this.newExpenseFormSubmission(x)} />}>
                        <ReactBootstrap.Button bsStyle='primary'>Submit a new expense</ReactBootstrap.Button>
                    </ReactBootstrap.ModalTrigger>
                </div>
                {main}
            </div>
        );
    }
});

export default MemberExpenses;

