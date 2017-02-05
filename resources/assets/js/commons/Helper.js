import $ from 'jquery';
import _ from 'lodash';
import config from '../config';

export default {
    getErrorMessages(errors) {
        let normalized = {};
        for (let name in errors) {
            let messages = errors[name];
            if (Array.isArray(messages) && messages[0]) {
                normalized[name] = messages[0];
            } else if (typeof messages === 'string' || messages) {
                normalized[name] = messages
            } else {
                normalized[name] = 'Error';
            }
        }
    },
    capitalise(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    },
    applyParams(string, params) {
        let temp;
        for (let param in params) {
            temp = string.replace(`\$\{${param}\}`, params[param]);
        }

        return temp
    },
    showSplashScreen() {
        $('.splash-screen').show();
    },
    hideSplashScreen() {
        $('.splash-screen').fadeOut();
    },
    validateEmail(email) {
        let validateEmail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        email = email.trim();
        return validateEmail.test(email);
    },
    errorsToArray(errors) {
        if (typeof errors == 'string') {
            return [errors];
        } else if (typeof errors == 'object') {
            let errorString = [];
            for (let key in errors) {
                errorString = errorString.concat(this.errorsToArray(errors[key]));
            }
            return errorString;
        }
    },
    getFirstError(xhr) {
        let errorsArray = [];
        if (xhr.responseJSON) {
            errorsArray = this.errorsToArray(xhr.responseJSON);
        }

        if (errorsArray[0] && errorsArray[0] !== '') {
            return errorsArray[0];
        }

        return 'A server error occurred. Please contact the administrator';
    },
    getUserName(user) {
        let name = `${user.first_name || ''} ${user.last_name || ''}`

        return name.trim() || user.email;
    },
    nl2br(str) {
        return str.replace(/\r\n|\n|\r/g, '<br />');
    }
};
