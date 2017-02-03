<?php

namespace App\Entities;

use Carbon\Carbon;

/**
 * Class Customer
 * @package App\Entities
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property string $phone
 * @property string $address
 * @property string $remember_token
 * @property bool $is_verify
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Customer extends BaseUser implements UserProvider
{
    protected $table = 'customers';
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'avatar',
        'phone',
        'address',
        'remember_token',
        'active',
        'verified',
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['is_verify' => 'bool', 'is_active' => 'bool'];
}
