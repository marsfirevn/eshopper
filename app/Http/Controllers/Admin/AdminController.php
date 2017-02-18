<?php

namespace App\Http\Controllers\Admin;

use App\Core\Repositories\Contracts\AdminRepositoryInterface as AdminRepository;
use App\Core\Repositories\Contracts\AdminRepositoryInterface;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    protected $repository;

    /**
     * AdminController constructor.
     *
     * @param AdminRepositoryInterface $repository
     */
    public function __construct(AdminRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get admin list
     */
    public function index()
    {
        $admins = $this->repository->all();
        return $this->response([
            'message' => 'Get admin list success',
            'admins' => $admins,
        ]);
    }
}
