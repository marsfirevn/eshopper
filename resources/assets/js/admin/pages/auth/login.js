import React from 'react';
import {withRouter, Link} from 'react-router';
import TextField from 'material-ui/TextField';
import RaisedButton from 'material-ui/RaisedButton';
import autobind from 'react-autobind';
import Config from '../../../config';

class Login extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.state = {
            email: '',
            password: '',
            errors: {},
            loginDisabled: true
        };
        this.redirectAfterLogin = this.props.route.redirect || '';
    }

    componentWillMount() {
        let email = this.props.router.location.query.email;
        if (email) {
            this.setState({email});
        }
    }

    componentDidMount() {
        let auth = this.context.auth;

        if (auth && auth.loggedIn) {
            this.props.router.push(this.redirectAfterLogin);
        } else {
            this.refs.email.focus();
        }
    }

    login() {
        let email = this.state.email.trim(),
            password = this.state.password;

        if (!this.validateLogin({email, password})) {
            return;
        }

        this.context.app.login({email, password}, (errors) => {
            if (errors) {
                this.setState({errors})
            } else {
                this.props.router.push(this.redirectAfterLogin);
            }
        });
    }

    onTextFieldChange(attr, event) {
        let state = {};
        state[attr] = event.target.value;
        this.setState(state, () => {
            this.setState({loginDisabled: !this.validateLogin(this.state)});
        });
    }

    onKeyDown(event) {
        if (event.keyCode == 13) {
            this.login();
        }
    }

    validateLogin(data) {
        return data.email.length > 0 && data.password.length > 0 && Helper.validateEmail(data.email);
    }

    render() {
        let auth = this.context.auth,
            title = this.props.route.title,
            logo = Config.logo;

        if (auth && auth.loggedIn) {
            return null;
        }

        return (
            <div className="login">
                <img className="logo-icon" src={logo}/>
                <div className="login-title">
                    <div className="title">{title}</div>
                    <hr className="underline"/>
                </div>
                <div className="login-box">
                    <div className="login-form">
                        <div className="row">
                            <div className="title">Email<span className="required"/></div>
                            <TextField
                                className="login-input email"
                                name="username"
                                type="text"
                                value={this.state.email}
                                ref="email"
                                hintText="Enter your email"
                                fullWidth={true}
                                errorStyle={{textAlign: 'left'}}
                                errorText={this.state.errors.email || this.state.errors.error}
                                onChange={this.onTextFieldChange.bind(this, 'email')}
                                onKeyDown={this.onKeyDown}
                            />
                        </div>
                        <div className="row">
                            <div className="title">Password<span className="required"/></div>
                            <TextField
                                className="login-input password"
                                name="password"
                                type="password"
                                value={this.state.password}
                                hintText="Enter your password"
                                fullWidth={true}
                                errorStyle={{textAlign: 'left'}}
                                errorText={this.state.errors.password}
                                onChange={this.onTextFieldChange.bind(this, 'password')}
                                onKeyDown={this.onKeyDown}
                            />
                        </div>
                    </div>
                    <div className="center">
                        <Link
                            className="forgot-password"
                            to={
                                this.state.email.length == 0
                                    ? '/password/email'
                                    : `/password/email?email=${this.state.email}`
                            }
                        >
                            Forgot your password?
                        </Link>
                        <RaisedButton
                            className="btn-login"
                            label="Login"
                            primary={true}
                            onClick={this.login}
                            disabled={this.state.loginDisabled}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

Login.contextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default withRouter(Login);
