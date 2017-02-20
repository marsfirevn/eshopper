import React from 'react';
import autobind from 'react-autobind';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import Navigation from './navigation';
import Header from './header';
import defaultTheme from '../../theme/default-theme';
import {Paper} from 'material-ui';

class App extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.store = {};
        this.state = {
            auth: props.auth.user
        };
    }

    componentWillMount() {
        let auth = this.props.auth;
        auth.onChange(this.onAuthUpdated);
    }

    onAuthUpdated(auth) {
        this.setState({auth});
    }

    getChildContext() {
        return {
            app: this,
            auth: this.state.auth
        }
    }

    getAuth(callback) {
        this.props.auth.getAuth(callback);
    }

    login(credential, callback) {
        this.props.auth.login(credential, callback);
    }

    getStore(key) {
        return key ? this.store[key] || null : this.store;
    }

    updateStore(key, value) {
        if (key) {
            this.store[key] = value;
        }
    }

    render() {
        let pathName = this.props.location.pathname;
        let loggedIn = this.props.auth.loggedIn;

        return (
            <MuiThemeProvider muiTheme={getMuiTheme(defaultTheme)}>
                <div className="wrapper">
                    {loggedIn ? <Navigation pathName={pathName}/> : null}

                    <div className="content-wrapper">
                        {loggedIn ? <Header pathName={pathName}/> : null}

                        <Paper className="main-body" zDepth={0}>
                            {this.props.children}
                        </Paper>
                    </div>
                </div>
            </MuiThemeProvider>
        );
    }
}

App.childContextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default App;
