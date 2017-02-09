<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 07/02/2017
 * Time: 22:14
 */

namespace App\Http\Controllers\Common\Auth\Traits;

use Auth;
use Illuminate\Http\Request;

/**
 * Class AuthenticateUsers
 * @package App\Http\Controllers\Common\Auth\Traits
 * @property $username string
 */
trait AuthenticateUsers
{
    /**
     * Get current auth
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuth()
    {
        $auth = $this->auth();
        return $this->response(['loggedIn' => ! empty($auth), 'user' => $auth]);
    }

    /**
     * Login
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $credentials = $this->getCredentials($request);

        if ($this->attemptLogin($credentials)) {
            return $this->wasAuthenticated();
        }

        return $this->responseLoginFailed();
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->guard()->logout();
        return redirect('/');
    }

    /**
     * After attempt
     * @return \Illuminate\Http\JsonResponse
     */
    protected function wasAuthenticated()
    {
        $user = $this->auth();

        if ($user->is_active) {
            return $this->response(compact('user'));
        }

        $this->logout();
        return $this->response(['error' => trans('auth.deactived')], 403);
    }

    /**
     * Login failed response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseLoginFailed()
    {
        return $this->response(['error' => trans('auth.failed')], 422);
    }

    /**
     * Attempt login
     * @param $credentials
     * @param bool $remember
     * @return bool
     */
    protected function attemptLogin($credentials, $remember = false)
    {
        return $this->guard()->attempt($credentials, $remember);
    }

    /**
     * Get credentials
     * @param Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Get username mode
     * @return string
     */
    protected function username()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }

    /**
     * Get session guard
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard($this->getGuard());
    }
}
