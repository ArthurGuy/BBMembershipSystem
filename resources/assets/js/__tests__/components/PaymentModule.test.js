
'use strict';


describe('PaymentModule', function() {

    it('renders correctly', function() {

        var React = require('react/addons');
        var TestUtils = React.addons.TestUtils;

        var PaymentModule = require('../../components/PaymentModule');

        var document = TestUtils.renderIntoDocument(<PaymentModule />);

        var module = TestUtils.findRenderedDOMComponentWithClass(document, 'multi-payment-form');

        //expect(module.getDOMNode().className).toContain("multi-payment-form");

    });

});