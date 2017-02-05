import React from 'react';
import ReactDOM from 'react-dom';
import {Route, Router, browserHistory} from 'react-router';
import $ from 'jquery';
import toastr from 'toastr';
import Helper from '../commons/Helper';
import {Auth} from '../commons/Auth';
import injectTapEventPlugin from 'react-tap-event-plugin';
import App from './components/app';

injectTapEventPlugin();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $("meta[name='_token']").attr('content')
    }
});

window.Helper = Helper;
let auth = new Auth();

function requireAuth(nextState, replace) {
    if (auth.loggedIn === false) {
        if (auth.error) {
            toastr.error(auth.error);
            auth.error = null;
        }

        replace({
            pathname: '/login',
            state: {
                returnUrl: nextState.location.pathname
            }
        });
    }
}

function authenticated(nextState, replace) {
    if (auth.loggedIn && auth.user) {
        replace({
            pathname: '/'
        });
    }
}

let routes = (
    <Router history={browserHistory}>
        <Route path='/' component={App}/>
    </Router>
);

auth.getAuth(() => {
    ReactDOM.render(routes, document.getElementById('app'));
});
