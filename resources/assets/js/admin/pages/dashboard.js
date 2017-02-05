import React from 'react';
import autobind from 'react-autobind';

class Dashboard extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);
    }

    render() {
        return (
            <div>Dashboard</div>
        );
    }
}

export default Dashboard;
