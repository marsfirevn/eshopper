<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Core\Repositories\AdminRepository;
use App\Http\Controllers\Common\Auth\BasePasswordController;

class PasswordController extends BasePasswordController
{
    protected $guard = 'admin';

    /**
     * PasswordController constructor.
     * @param AdminRepository $repository
     */
    public function __construct(AdminRepository $repository)
    {
        $this->middleware($this->authMiddleware());
        parent::__construct($repository);
    }
}
