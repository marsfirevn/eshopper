import React from 'react';
import autobind from 'react-autobind';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import Navigation from './navigation';
import Header from './header';
import {white, grey700} from 'material-ui/styles/colors';

const mainColor = '#00BBE5';
const customTheme = {
    palette: {
        primary1Color: mainColor,
        primary2Color: mainColor,
        accent1Color: mainColor
    },
    appBar: {
        color: white,
        textColor: mainColor
    },
    flatButton: {
        secondaryTextColor: grey700
    },
    tableHeaderColumn: {
        textColor: white,
        height: '46px'
    },
    inkBar: {
        backgroundColor: white
    }
};

const muiTheme = getMuiTheme(customTheme);

class App extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);
    }

    render() {
        let pathname = this.props.location.pathname;

        return (
            <MuiThemeProvider muiTheme={muiTheme}>
                <div className="wrapper">
                    <Navigation pathName={pathname}/>

                    <div className="content-wrapper">
                        <Header/>
                    </div>
                </div>
            </MuiThemeProvider>
        );
    }
}

export default App;
