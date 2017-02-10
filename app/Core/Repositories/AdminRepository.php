<?php

namespace App\Core\Repositories;

use App\Entities\Admin;
use App\Core\Repositories\Traits\PasswordTrait;

class AdminRepository extends Repository
{
    use PasswordTrait;

    protected $entityName = Admin::class;
}
