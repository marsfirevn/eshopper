import React from 'react';
import $ from 'jquery';

export class Auth {
    getAuth(callback) {
        $.ajax({
            url: '/api/auth',
            type: 'GET',
            success: (response) => {
                this.loggedIn = response.loggedIn;
                this.user = response.user;
                if (typeof this.updateAuth === 'function') {
                    this.updateAuth(response.user);
                }
                if (typeof callback === 'function') {
                    callback(response);
                }
            },
            error: (errors) => {
                this.error = Helper.getFirstError(errors);
                this.loggedIn = false;
                if (typeof this.updateAuth === 'function') {
                    this.updateAuth({loggedIn: false});
                }
                if (typeof callback === 'function') {
                    callback({loggedIn: false});
                }
            }
        });
    }

    onChange(func) {
        this.updateAuth = func;
    }

    login(credential, callback) {
        $.ajax({
            url: '/api/login',
            method: 'POST',
            data: credential,
            success: (response) => {
                this.loggedIn = true;
                this.user = response.user;
                if (typeof this.updateAuth === 'function') {
                    this.updateAuth(response.user);
                }
                if (typeof callback === 'function') {
                    callback(null);
                }
            },
            error: (response) => {
                this.loggedIn = false;
                this.user = null;
                if (typeof callback === 'function') {
                    callback(response.responseJSON);
                }
            }
        });
    }
}

export const authenticate = (ComposedComponent, auth) => class Authenticate extends React.Component {
    render() {
        return <ComposedComponent {...this.props} auth={auth}/>
    }
};

export default Auth;
