import React from 'react';



class Select extends React.Component {

    render() {

        var options = [];

        for (var i in this.props.options) {
            options.push(<option value={this.props.options[i]['key']} key={this.props.options[i]['key']}>{this.props.options[i]['value']}</option>);
        }

        var label = '';
        if (this.props.label) {
            label = <label className="control-label"><span>{this.props.label}</span></label>;
        }

        var help = '';

        if (this.props.help) {
            help = <span className="help-block">{this.props.help}</span>;
        }

        var fieldStyle = 'form-group';
        if (this.props.bsStyle) {
            fieldStyle += ' has-'+this.props.bsStyle;
        }

        return (

            <div className={fieldStyle}>
                {label}
                <select className="form-control" value={this.props.value} onChange={this.props.onChange}>
                    { options }
                </select>
                { help }
            </div>
        );
    }

}


export default Select;