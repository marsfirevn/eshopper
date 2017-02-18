<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 12/02/2017
 * Time: 16:15
 */

namespace App\Http\Controllers\Common\Profile;

use App\Core\Repositories\Repository;
use App\Core\Uploader\AvatarUploader;
use App\Entities\BaseUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseProfileController extends Controller
{
    protected $repository;

    /**
     * BaseProfileController constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Update admin profile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $auth = $this->auth();
        $this->validateUpdateProfile($request);
        $this->saveAvatar($request, $auth);
        $auth = $this->repository->update($this->getUpdateData($request), $auth);
        return $this->response([
            'message' => 'You have been update profile successfully!',
            'auth' => $auth,
        ]);
    }

    /**
     * Save avatar
     * @param Request $request
     * @param BaseUser $auth
     * @return false|string
     */
    protected function saveAvatar(Request $request, BaseUser $auth)
    {
        $resultUpload = false;

        if ($file = $request->file('avatar')) {
            $resultUpload = (new AvatarUploader($auth, $file))->make();
        }

        return $resultUpload;
    }

    /**
     * Get update profile data
     * @param Request $request
     * @return array
     */
    protected function getUpdateData(Request $request)
    {
        $data = $request->only(['first_name', 'last_name']);

        if ($request->get('password')) {
            $data['password'] = bcrypt($request->get('password'));
        }

        return $data;
    }

    /**
     * Validate update profile request
     * @param Request $request
     */
    protected function validateUpdateProfile($request)
    {
        $this->validate($request, $this->getUpdateProfileRules());
    }

    /**
     * Get validate update profile request rules
     * @return array
     */
    protected function getUpdateProfileRules()
    {
        return [
            'first_name' => 'string',
            'last_name' => 'string',
            'password' => 'string|confirmed',
        ];
    }
}
