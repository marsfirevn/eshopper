<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 23/01/2017
 * Time: 22:53
 */

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Class Admin
 *
 * @package App\Entities
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property bool $is_super
 * @property bool $invite_token
 * @property bool $remember_token
 * @property bool $active
 * @property bool $verified
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Admin extends BaseUser implements
    UserProvider,
    AuthenticatableContract,
    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Authorizable;

    protected $table = 'admins';
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'address',
        'is_super',
        'invite_token',
        'remember_token',
        'active',
        'verified',
    ];
    protected $hidden = ['password', 'invite_token', 'remember_token'];
}
