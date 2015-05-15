import React from 'react';



class Select extends React.Component {

    render() {

        var options = [];

        for (var i in this.props.options) {
            options.push(<option value={this.props.options[i]['key']} key={this.props.options[i]['key']}>{this.props.options[i]['value']}</option>);
        }

        return (
            <div className="form-group">
                <select className="form-control" value={this.props.value} onChange={this.props.onChange}>
                    { options }
                </select>
            </div>
        );
    }

}


export default Select;