<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 05/02/2017
 * Time: 13:29
 */

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Common\Auth\BaseLoginController;

class LoginController extends BaseLoginController
{
    protected $guard = 'admin';
}
