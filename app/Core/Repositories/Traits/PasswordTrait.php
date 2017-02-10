<?php

namespace App\Core\Repositories\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordTrait
 * @package App\Core\Repositories\Traits
 * @property Model $entity
 */
trait PasswordTrait
{
    /**
     * Reset password validate email and token
     * @param string $email
     * @param string $token
     * @return mixed
     */
    public function isValidInvitation($email = '', $token = '')
    {
        return $this->entity->where(['email' => $email, 'invite_token' => $token])->first();
    }

    /**
     * Register password for invitation
     * @param $email
     * @param $password
     */
    public function registerPassword($email, $password)
    {
        $this->entity
            ->where('email', $email)
            ->update([
                'password' => bcrypt($password),
                'invite_token' => null,
                'is_active' => 1,
            ]);
    }
}
