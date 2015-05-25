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
        this.props.expenses.fetch({ data: { user_id: this.props.userId } });
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

    render: function () {
        var main = <p>Bought an item for Build Brighton, claim the money back here</p>;
        var expenses = this.props.expenses;


        var expenseItems = expenses.map(function (expense) {
            return (<ExpenseItem key={expense.get('id')} expense={expense} />);
        }, this);

        if (expenses.length) {
            main = (
                <table fill className="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {expenseItems}
                    </tbody>
                </table>
            );
        }

        var header = (
            <h3>Reclaim Expenses</h3>
        );
        var footer = (
            <ReactBootstrap.ModalTrigger modal={<NewExpenseModal collection={this.props.expenses} userId={this.props.userId} />}>
                <ReactBootstrap.Button bsStyle='primary'>Submit a new expense</ReactBootstrap.Button>
            </ReactBootstrap.ModalTrigger>
        );

        return (
            <ReactBootstrap.Panel header={header} footer={footer}>
                {main}
            </ReactBootstrap.Panel>
        );
    }
});

export default MemberExpenses;

