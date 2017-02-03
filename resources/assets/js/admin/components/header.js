import React from 'react';
import autobind from 'react-autobind';

class Header extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);
    }

    render() {
        return (
            <div>Header</div>
        );
    }
}

export default Header;
