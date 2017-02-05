<?php

namespace App\Http\Controllers\Common\Auth;

use App\Http\Controllers\Common\Auth\Traits\AuthenticateUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class BaseLoginController
 * @package App\Http\Controllers\Common\Auth
 * @property $guard
 * @property $username
 */
abstract class BaseLoginController extends Controller
{
    use AuthenticateUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Validate login request
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required',
        ]);
    }
}
