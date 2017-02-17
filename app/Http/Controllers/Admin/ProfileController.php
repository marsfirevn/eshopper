<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 12/02/2017
 * Time: 16:19
 */

namespace App\Http\Controllers\Admin;

use App\Core\Repositories\AdminRepository;
use App\Http\Controllers\Common\Profile\BaseProfileController;

class ProfileController extends BaseProfileController
{
    protected $guard = 'admin';

    public function __construct(AdminRepository $repository)
    {
        $this->middleware($this->authMiddleware());
        parent::__construct($repository);
    }
}
