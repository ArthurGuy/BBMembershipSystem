import React from 'react';


class Tick extends React.Component {

    render() {

        if (this.props.ticked) {
            return (
                <span className="glyphicon glyphicon-ok" title={this.props.title}></span>
            )
        }
        return null;
    }

}

export default Tick;