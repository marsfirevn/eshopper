<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 10/02/2017
 * Time: 23:32
 */

namespace App\Http\Controllers\Common\Auth;

use App\Core\Repositories\Repository;
use App\Events\Auth\ResetPasswordTokenCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Password;

abstract class BasePasswordController extends Controller
{
    /**
     * Repository
     * @var Repository $repository
     */
    protected $repository;
    protected $table;

    /**
     * BasePasswordController constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Process reset password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEmail(Request $request)
    {
        $this->validateEmailAddress($request);

        $broker = Password::broker($this->getBroker());
        $user = $broker->getUser($this->getProvidedEmail($request));
        $token = Password::getRepository()->create($user);

        event(new ResetPasswordTokenCreated($user->email, $token, $this->getGuard()));

        return $this->response(trans('passwords.sent'));
    }

    /**
     * Check invalid email
     * @param Request $request
     */
    protected function validateEmailAddress(Request $request)
    {
        $this->validate($request, $this->getEmailValidationRules());
    }

    /**
     * Get validation rules for post email
     * @return array
     */
    protected function getEmailValidationRules()
    {
        return [
            'email' => 'required|exists:' . $this->getBroker(),
        ];
    }

    /**
     * Get table name of user
     * @return string|null
     */
    protected function getBroker()
    {
        return config('auth.guards.' . $this->getGuard() . '.providers');
    }

    /**
     * Get provided email
     * @param Request $request
     * @return mixed
     */
    protected function getProvidedEmail(Request $request)
    {
        return $request->only('email');
    }
}
