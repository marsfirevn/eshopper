<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Core\Repositories\Contracts\AdminRepositoryInterface;
use App\Http\Controllers\Common\Auth\BasePasswordController;

class PasswordController extends BasePasswordController
{
    protected $guard = 'admin';

    /**
     * PasswordController constructor.
     * @param AdminRepositoryInterface $repository
     */
    public function __construct(AdminRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
