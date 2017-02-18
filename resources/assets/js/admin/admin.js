import React from 'react';
import ReactDOM from 'react-dom';
import {Route, Router, browserHistory, IndexRedirect} from 'react-router';
import $ from 'jquery';
import toastr from 'toastr';
import Helper from '../commons/Helper';
import {Auth, authenticate} from '../commons/Auth';
import injectTapEventPlugin from 'react-tap-event-plugin';
import App from './components/app';
import Login from './pages/auth/login';
import Dashboard from './pages/dashboard';
import ResetPassword from './pages/auth/reset-password';
import UpdateNewPassword from './pages/auth/update-new-password';
import AdminProfile from './pages/profile/admin-profile';

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
        <Route path="/" component={authenticate(App, auth)}>
            <Route
                path="login"
                component={Login}
                type="admin"
                title="Welcome to E-shopper!"
                redirect=''
                onEnter={authenticated}
            />
            <Route path="password/email" component={ResetPassword}/>
            <Route path="password/reset/:token" component={UpdateNewPassword}/>

            <Route path="" onEnter={requireAuth}>
                <IndexRedirect to='dashboard'/>
                <Route path='dashboard' component={Dashboard}/>
                <Route path='orders' component={Dashboard}/>
                <Route path='products' component={Dashboard}/>
                <Route path='categories' component={Dashboard}/>
                <Route path='customers' component={Dashboard}/>
                <Route path='admins' component={Dashboard}/>
                <Route path='profile' component={AdminProfile}/>
            </Route>
        </Route>
    </Router>
);

auth.getAuth(() => {
    ReactDOM.render(routes, document.getElementById('app'));
});
