
'use strict';


describe('Form/Select', function() {

    it('renders label correctly', function() {

        var React = require('react/addons');
        var TestUtils = React.addons.TestUtils;

        var Select = require('../../../components/form/Select');

        var document = TestUtils.renderIntoDocument(<Select label="Select Label" />);

        var label = TestUtils.findRenderedDOMComponentWithTag(document, 'label');
        expect(label.getDOMNode().textContent).toBe("Select Label");

    });

    it('renders help correctly', function() {

        var React = require('react/addons');
        var TestUtils = React.addons.TestUtils;

        var Select = require('../../../components/form/Select');

        var document = TestUtils.renderIntoDocument(<Select help="Some help text" />);

        var label = TestUtils.findRenderedDOMComponentWithTag(document, 'span');
        expect(label.getDOMNode().textContent).toBe("Some help text");

    });

    it('adds style class correctly', function() {

        var React = require('react/addons');
        var TestUtils = React.addons.TestUtils;

        var Select = require('../../../components/form/Select');

        var document = TestUtils.renderIntoDocument(<Select bsStyle="success" />);

        var label = TestUtils.findRenderedDOMComponentWithTag(document, 'div');

        expect(label.getDOMNode().className).toContain("has-success");
    });

    it('renders options correctly', function() {

        var React = require('react/addons');
        var TestUtils = React.addons.TestUtils;

        var Select = require('../../../components/form/Select');

        var selectOptions = [
            {key: 'option1', value:'Value 1'},
            {key: 'option2', value:'Value 2'},
            {key: 'option3', value:'Value 3'}
        ];

        var document = TestUtils.renderIntoDocument(<Select options={selectOptions} />);

        var select = TestUtils.findRenderedDOMComponentWithTag(document, 'select');
        expect(select.getDOMNode().children.item(0).textContent).toBe("Value 1");
        expect(select.getDOMNode().children.item(0).value).toBe("option1");

    });

});
