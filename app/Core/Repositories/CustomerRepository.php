<?php

namespace App\Core\Repositories;

use App\Entities\Customer;
use App\Core\Repositories\Traits\PasswordTrait;

class CustomerRepository extends Repository
{
    use PasswordTrait;

    protected $entityName = Customer::class;
}
