//import React from 'react';
'use strict';
var React = require('react');
var PaymentTable = require('./PaymentTable');


class FilterablePaymentTable extends React.Component {

    state = {
        items: [
            {id:1, date:'2015-02-01', user:{id:1, name:'John Doe'}, reason:'reason', method:'method', amount:50, reference:'ref', status:'paid'},
            {id:2, date:'2015-02-01', user:{id:1, name:'John Doe'}, reason:'reason', method:'method', amount:50, reference:'ref', status:'paid'},
            {id:3, date:'2015-02-01', user:{id:2, name:'Steve'}, reason:'reason', method:'method', amount:50, reference:'ref', status:'paid'}
        ],
        filterDate: null,
        filterMember: null,
        filterReason: 'subscription'
    };

    componentDidMount() {
        //global.jQuery = require('jquery');
        require('select2');

        //Convert all the dropdowns in this element to select2 dropdowns
        jQuery(React.findDOMNode(this)).find('select').select2({dropdownAutoWidth:false});


    }

    handleDateChange(event) {
        this.setState({filterDate: event.target.value});
    }

    handleMemberChange(event) {
        this.setState({filterMember: event.target.value});
    }

    handleReasonChange(event) {
        this.setState({filterReason: event.target.value});
    }

    render() {

        return (
            <div>
                <form>
                    <select value={this.state.filterDate} onChange={this.handleDateChange}></select>
                    <select value={this.state.filterMember} onChange={this.handleMemberChange}></select>
                    <select value={this.state.filterReason} onChange={this.handleReasonChange}><option value=""></option><option value="subscription">Subscription</option><option value="balance">Balance</option></select>
                </form>
                <PaymentTable payments={this.state.items} />
            </div>
        );
    }

}

export default FilterablePaymentTable;