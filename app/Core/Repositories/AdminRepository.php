<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 19/02/2017
 * Time: 08:05
 */

namespace App\Core\Repositories;

use App\Core\Repositories\Contracts\AdminRepositoryInterface;
use App\Entities\Admin;

class AdminRepository extends Repository implements AdminRepositoryInterface
{
    protected $modelName = Admin::class;
}
