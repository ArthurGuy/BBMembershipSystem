import React from 'react';
var ReactBootstrap = require('react-bootstrap');
var Select = require('../form/Select');
var ValidationMixin = require('react-validation-mixin');
var Joi = require('joi');
require('backbone-model-file-upload');
var $ = require('jquery');

const NewExpenseModal = React.createClass({

    mixins: [ValidationMixin],

    validatorTypes:  {
        category: Joi.string().required().label('Category'),
        description: Joi.string().required().min(2).label('Description'),
        amount: Joi.number().precision(2).positive().max(1000).required().label('Amount'),
        expense_date: Joi.date().max('now').format('D/M/YY').required().label('Purchase Date'),
        file:  Joi.string().required().label('Receipt')
    },

    getInitialState: function() {
        return {
            category: null,
            description: null,
            amount: null,
            expense_date: null,
            file: null,
            requestInProgress: false,
            dirty: [],
            submitAttempt: false
        };
    },

    handleChange: function(key) {
        return function (e) {
            var state = {};
            state[key] = e.target.value;
            state['dirty'] = this.state.dirty;
            state['dirty'][key] = true;

            //Validate the form when anything changes
            this.setState(state, () => { this.validate(); });
        }.bind(this);
    },

    handleSubmit: function(event) {
        event.preventDefault();

        this.setState({submitAttempt:true, requestInProgress:true});

        var onValidate = function(error, validationErrors) {
            if (error) {

                this.setState({requestInProgress:false});

            } else {

                var submitAmount = this.state.amount * 100; //values are stored in pence

                var file = $('#fileUpload')[0].files[0];

                //Create an instance of an expense model to save our data to
                var expense = new this.props.collection.model();

                //On an error display the details
                expense.on('error', (model, error) => {
                    var errors = jQuery.parseJSON(error.responseText);
                    this.setState({errors:errors, requestInProgress:false});
                });

                //If the model syncs successfully close the modal and save back to the collection
                expense.on('sync', () => {
                    this.props.onRequestHide();
                    this.props.collection.add(expense);
                    this.setState({requestInProgress:false});
                });

                expense.on('progress', (data) => {console.log('File Progress: ', data)});
                expense.on('all', (data) => {console.log('All: ', data)});

                //Save the new model to the server
                expense.save({description:this.state.description, category:this.state.category, amount:submitAmount, expense_date:this.state.expense_date, file:file}, {wait: true});
                //this.setState({requestInProgress:false});
            }

        }.bind(this);

        //Validate and submit the data
        this.validate(onValidate);
    },

    fieldStyle: function(key) {
        if (this.state.dirty[key] || this.state.submitAttempt) {
            if (this.isValid(key)) {
                return 'success';
            } else {
                return 'error';
            }
        }
    },

    validationMessage: function(key) {
        if ( ! this.state.dirty[key] && ! this.state.submitAttempt) {
            return;
        }
        //return the first of the validation messages
        return this.getValidationMessages(key).pop();
    },

    render: function() {

        var dropdownOptions = [
            {key:'', value:''},
            {key:'consumables', value:'Consumables'},
            {key:'food', value:'Food'},
            {key:'equipment-repair', value:'Equipment Repair'},
            {key:'tools', value:'Tools'},
            {key:'infrastructure', value:'Infrastructure'},
        ];

        var feedback = null;
        if (this.state.feedback) {
            feedback = <ReactBootstrap.Alert bsStyle='danger'>{this.state.feedback}</ReactBootstrap.Alert>;
        }

        return (
            <ReactBootstrap.Modal {...this.props} title='Submit a New Expense' footer animation={false}>
                <div className='modal-body'>

                    {feedback}

                    <Select options={dropdownOptions} value={this.state.category} label="Category" onChange={this.handleChange('category')} help={this.validationMessage('category')} bsStyle={this.fieldStyle('category')} />

                    <ReactBootstrap.Input type='text' label='Description' help={this.validationMessage('description')} placeholder='New saw blades' value={this.state.description} onChange={this.handleChange('description')} bsStyle={this.fieldStyle('description')} />

                    <ReactBootstrap.Input type='text' label='Amount' help={this.validationMessage('amount')} placeholder='4.99' value={this.state.amount} onChange={this.handleChange('amount')} bsStyle={this.fieldStyle('amount')} />

                    <ReactBootstrap.Input className='js-date-select' type='text' label='Date' help={this.validationMessage('expense_date')} placeholder='24/3/15' value={this.state.expense_date} onChange={this.handleChange('expense_date')} bsStyle={this.fieldStyle('expense_date')} />

                    <ReactBootstrap.Input id="fileUpload" type='file' label='Receipt' help={this.validationMessage('file')} value={this.state.file} onChange={this.handleChange('file')} bsStyle={this.fieldStyle('file')} />

                </div>
                <div className='modal-footer'>
                    <div className={this.state.requestInProgress ? 'has-feedback has-success' : 'hidden'}>
                        <span className="help-block">Please wait, processing...</span>
                    </div>
                    <ReactBootstrap.Button onClick={this.props.onRequestHide}>Close</ReactBootstrap.Button>
                    <ReactBootstrap.Button bsStyle='primary' onClick={this.handleSubmit} disabled={this.state.requestInProgress}>Save</ReactBootstrap.Button>
                </div>

            </ReactBootstrap.Modal>
        );
    }
});

export default NewExpenseModal;