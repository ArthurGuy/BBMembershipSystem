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
        description: Joi.string().required().min(5).label('Description'),
        amount: Joi.number().precision(2).positive().max(200).required().label('Amount'),
        date: Joi.date().format('YYYY-MM-DD').required().label('Purchase Date'),
        file:  Joi.string().label('Receipt')
    },

    getInitialState: function() {
        return {
            category: null,
            description: null,
            amount: null,
            date: '2015-05-10',
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
        this.setState({submitAttempt:true});
        var onValidate = function(error, validationErrors) {
            if (!error) {
                var submitAmount = this.state.amount * 100; //values are stored in pence
                var file = $('#fileUpload')[0].files[0];
                this.props.collection.create({description:this.state.description, category:this.state.category, amount:submitAmount, expense_date:this.state.date, file:file}, {wait: true});
                this.props.collection.on('progress', console.log);
                this.props.onRequestHide();
            }
        }.bind(this);
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

        var dropdownOptions = [{key:'', value:''},{key:'consumables', value:'Consumables'}, {key:'food', value:'Food'}];

        return (
            <ReactBootstrap.Modal {...this.props} title='Submit a New Expense' footer animation={false}>
                <div className='modal-body'>

                    <Select options={dropdownOptions} value={this.state.category} label="Category" onChange={this.handleChange('category')} help={this.validationMessage('category')} bsStyle={this.fieldStyle('category')} />

                    <ReactBootstrap.Input type='text' label='Description' help={this.validationMessage('description')} placeholder='New saw blades' value={this.state.description} onChange={this.handleChange('description')} bsStyle={this.fieldStyle('description')} />

                    <ReactBootstrap.Input type='text' label='Amount' help={this.validationMessage('amount')} placeholder='4.99' value={this.state.amount} onChange={this.handleChange('amount')} bsStyle={this.fieldStyle('amount')} />

                    <ReactBootstrap.Input type='text' label='Date' help={this.validationMessage('date')} placeholder='2015-03-24' value={this.state.date} onChange={this.handleChange('date')} bsStyle={this.fieldStyle('date')} />

                    <ReactBootstrap.Input id="fileUpload" type='file' label='Receipt' help={this.validationMessage('file')} value={this.state.file} onChange={this.handleChange('file')} bsStyle={this.fieldStyle('file')} />

                </div>
                <div className='modal-footer'>
                    <ReactBootstrap.Button onClick={this.props.onRequestHide}>Close</ReactBootstrap.Button>
                    <ReactBootstrap.Button bsStyle='primary' onClick={this.handleSubmit}>Save</ReactBootstrap.Button>
                </div>
                {this.state.feedback}
            </ReactBootstrap.Modal>
        );
    }
});

export default NewExpenseModal;