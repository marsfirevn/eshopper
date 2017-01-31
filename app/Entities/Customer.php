<?php

namespace App\Entities;

use Carbon\Carbon;

/**
 * Class Customer
 *
 * @package App\Entities
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $address
 * @property string $remember_token
 * @property bool $active
 * @property bool $verified
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
        'phone',
        'address',
        'remember_token',
        'active',
        'verified',
    ];
    protected $hidden = ['password', 'remember_token'];
}
