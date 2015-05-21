
'use strict';

//jest.autoMockOff();
jest.dontMock('../components/form/Select');

describe('foo', function() {

    it('renders correctly', function() {

        var React = require('react/addons');
        var TestUtils = React.addons.TestUtils;

        var Select = require('../components/form/Select');

        var select = TestUtils.renderIntoDocument(
            <Select />
        );

        var label = TestUtils.findRenderedDOMComponentWithTag(select, 'select');
        return expect(select.getDOMNode().textContent).toEqual('Off');

        var input = TestUtils.findRenderedDOMComponentWithTag(select, 'select');
        TestUtils.Simulate.change(input);
        expect(label.getDOMNode().textContent).toEqual('boo');

    });

    it('should work', function() {
        return expect(1).toEqual(2);
    });
});

describe('sum', function() {
    it('adds 1 + 2 to equal 3', function() {
        var sum = require('../sum');
        expect(sum(1, 2)).toBe(3);
    });
});