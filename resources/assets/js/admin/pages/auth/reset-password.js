import React from 'react';
import autobind from 'react-autobind';
import Config from '../../../config';
import $ from 'jquery';
import {TextField, RaisedButton} from 'material-ui';
import toastr from 'toastr';

class ResetPassword extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.state = {
            email: '',
            errors: {},
            resetDisabled: true
        };
    }

    onTextFieldChange(attr, event) {
        let state = {};
        state[attr] = event.target.value;
        this.setState(state, () => {
            this.setState({resetDisabled: !this.validateReset(this.state)});
        });
    }

    onKeyDown(event) {
        if (event.keyCode == 13) {
            this.sendResetPasswordEmail();
        }
    }

    validateReset(data) {
        return data.email.length > 0 && Helper.validateEmail(data.email);
    }

    sendResetPasswordEmail() {
        let email = this.state.email;
        $.ajax({
            url: '/password/email',
            type: 'POST',
            data: {email},
            success: (response) => {
                toastr.success(response);
                this.props.router.push(`/login?email=${email}`);
            },
            error: (response) => this.setState({
                errors: response.responseJSON
            })
        });
    }

    render() {
        if (this.context.auth) {
            return null;
        }

        let title = 'Reset password';

        return (
            <div className="login">
                <img className="logo-icon" src={Config.logo}/>
                <div className="login-title">
                    <div className="title">{title}</div>
                    <hr className="underline"/>
                    <div className="sub-title">
                        Enter your register email and check mailbox to get reset password link
                    </div>
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
                    </div>
                    <div className="center">
                        <RaisedButton
                            className="btn-login"
                            label="Reset"
                            primary={true}
                            onClick={this.sendResetPasswordEmail}
                            disabled={this.state.resetDisabled}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

ResetPassword.contextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default ResetPassword;
