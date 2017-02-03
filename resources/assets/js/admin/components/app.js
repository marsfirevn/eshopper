import React from 'react';
import autobind from 'react-autobind';

class App extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);
    }

    render() {
        return (
            <div></div>
        );
    }
}

export default App;
