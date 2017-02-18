import React from 'react';
import autobind from 'react-autobind';
import Config from '../../../config';
import $ from 'jquery';
import toastr from 'toastr';
import {TextField, RaisedButton} from 'material-ui';

class UpdateNewPassword extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.state = {
            token: null,
            email: null,
            errors: null,
            resetDisabled: true
        };
    }

    componentWillMount() {
        if (this.context.auth) {
            toastr.success('You are logged in! You should change password in here!');
            this.props.router.push('/profile');
        }

        let token = this.props.router.params['token'];
        let email = this.props.router.location.query['email'];
        let errors = {email: '', newPassword: ''};
        if (!email) {
            errors = {email: 'Cannot found email! Please contact administrator to help!'}
        }

        this.setState({token, email, errors, newPassword: '', newPasswordConfirmation: ''});
    }

    onTextFieldChange(attr, event) {
        let state = {};
        state[attr] = event.target.value;
        this.setState(state, () => {
            this.setState({resetDisabled: !this.validateReset(this.state)});
        });
    }

    changePassword() {
        let state = this.state;
        if (this.validateReset(state)) {
            let data = new FormData(document.getElementById('reset-password'));
            $.ajax({
                url: `/password/reset/${state.token}`,
                type: 'POST',
                data,
                contentType: false,
                processData: false,
                success: () => {
                    toastr.success('Save password successfully!');
                    //this.props.router.push(`/login?email=${state.email}`);
                    window.location.href = '/dashboard';
                },
                error: (errors) => {
                    toastr.error(Helper.getFirstError(errors));
                    this.setState({
                        errors: errors.responseJSON
                    });
                }
            });
        }
    }

    validateReset(data) {
        return data.newPassword.length > 0 && data.newPassword === data.newPasswordConfirmation;
    }

    render() {
        let title = this.props.title || 'Change password';
        let email = this.state.email;
        let token = this.state.token;

        return (
            <div className="login">
                <img className="logo-icon" src={Config.logo}/>
                <div className="login-title">
                    <div className="title">{title}</div>
                    <hr className="underline"/>
                    <div className="sub-title">
                        Enter enter your new password for "{email}"
                    </div>
                </div>
                <div className="login-box">
                    <form className="login-form" id="reset-password" action="#" method="post">
                        <input type="hidden" name="_method" value="PUT"/>
                        <input type="hidden" name="email" value={email}/>
                        <input type="hidden" name="token" value={token}/>

                        <div className="row">
                            <div className="title">New password<span className="required"/></div>
                            <TextField
                                className="login-input email"
                                hintText="Enter new password"
                                name="password"
                                ref="password"
                                type="password"
                                value={this.state.newPassword}
                                fullWidth={true}
                                errorStyle={{textAlign: 'left'}}
                                errorText={this.state.errors.newPassword || ''}
                                onChange={this.onTextFieldChange.bind(this, 'newPassword')}
                            />
                        </div>
                        <div className="row">
                            <div className="title">Confirm new password<span className="required"/></div>
                            <TextField
                                className="login-input email"
                                hintText="Enter new password"
                                name="password_confirmation"
                                type="password"
                                value={this.state.newPasswordConfirmation}
                                fullWidth={true}
                                onChange={this.onTextFieldChange.bind(this, 'newPasswordConfirmation')}
                            />
                        </div>
                    </form>
                    <div className="center">
                        <div className="button-group">
                            <RaisedButton
                                className="btn-login"
                                label="Update"
                                primary={true}
                                onClick={this.changePassword}
                                disabled={this.state.resetDisabled}
                            />
                            <RaisedButton
                                label="Cancel"
                                onClick={() => this.props.router.push(`/login?email=${email}`)}
                            />
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

UpdateNewPassword.contextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default UpdateNewPassword;
