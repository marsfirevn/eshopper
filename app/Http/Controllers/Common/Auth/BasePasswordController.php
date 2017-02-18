<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 10/02/2017
 * Time: 23:32
 */

namespace App\Http\Controllers\Common\Auth;

use App\Core\Repositories\Contracts\RepositoryInterface;
use App\Core\Repositories\Repository;
use App\Entities\BaseUser;
use App\Events\Auth\ResetPasswordTokenCreated;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
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
    protected $username = 'email';

    /**
     * BasePasswordController constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
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
        $user = $broker->getUser($this->getProvidedUsername($request));
        $token = Password::getRepository()->create($user);

        event(new ResetPasswordTokenCreated($user->email, $token, $this->getGuard()));

        return $this->response(trans('passwords.sent'));
    }

    /**
     * Update password for reset password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $this->validateResetPassword($request);

        if ($this->invalidToken($this->getProvidedUsername($request), $request->get('token'))) {
            return $this->response(trans('passwords.token'), 400);
        }

        $credentials = $this->getResetCredentials($request);
        $response = Password::broker($this->getBroker())->reset($credentials, function (BaseUser $user, $password) {
            $this->storePasswordAndLogin($user, $password);
        });

        if ($response === Password::PASSWORD_RESET) {
            return $this->response(trans('passwords.reset'));
        }

        return $this->response(trans('passwords.user'), 400);
    }

    /**
     * Reset the given user's password.
     * @param BaseUser|CanResetPassword|Authenticatable $user
     * @param string $password
     * @return void
     */
    protected function storePasswordAndLogin($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => str_random(60),
        ])->save();

        Auth::guard($this->getGuard())->login($user);
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
     * Check invalid request password
     * @param Request $request
     */
    protected function validateResetPassword(Request $request)
    {
        $this->validate($request, $this->getResetPasswordValidationRules());
    }

    /**
     * Get validation rules for post email
     * @return array
     */
    protected function getEmailValidationRules()
    {
        return [
            $this->username() => 'required|exists:' . $this->getBroker(),
        ];
    }

    /**
     * Get validation rules for update password from reset
     * @return array
     */
    protected function getResetPasswordValidationRules()
    {
        return [
            $this->username() => 'required|exists:' . $this->getBroker(),
            'password' => 'required|confirmed',
        ];
    }

    /**
     * Check invalid token
     * @param string $providedUsername
     * @param string $token
     * @return bool
     */
    protected function invalidToken($providedUsername, $token)
    {
        $broker = $this->getBroker();
        return !Password::broker($broker)->tokenExists(Password::broker($broker)->getUser($providedUsername), $token);
    }

    /**
     * Get reset credentials
     * @param Request $request
     * @return array
     */
    protected function getResetCredentials(Request $request)
    {
        return $request->only('email', 'password', 'password_confirmation', 'token');
    }

    /**
     * Get table name of user
     * @return string|null
     */
    protected function getBroker()
    {
        return config('auth.guards.' . $this->getGuard() . '.provider');
    }

    /**
     * Get provided email
     * @param Request $request
     * @return mixed
     */
    protected function getProvidedUsername(Request $request)
    {
        return $request->only($this->username());
    }

    /**
     * Get user name type is email or another
     * @return string
     */
    protected function username()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }
}
