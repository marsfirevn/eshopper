<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 23/01/2017
 * Time: 22:53
 */

namespace App\Entities;

use App\Entities\Contracts\UserProvider;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Class Admin
 * @package App\Entities
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property string $remember_token
 * @property bool $is_super
 * @property bool $is_verify
 * @property bool $is_active
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
        'is_super',
        'remember_token',
        'is_verify',
        'is_active',
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['is_super' => 'bool', 'is_verify' => 'bool', 'is_active' => 'bool'];
}
