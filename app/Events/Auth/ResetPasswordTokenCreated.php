<?php

namespace App\Events\Auth;

use Illuminate\Queue\SerializesModels;

/**
 * Class ResetPasswordTokenCreated
 * @package App\Events\Auth
 */
class ResetPasswordTokenCreated
{
    use SerializesModels;

    public $email;
    public $token;
    public $userType;

    /**
     * Create a new event instance.
     * @param string $email
     * @param string $token
     * @param string $userType
     */
    public function __construct($email, $token, $userType)
    {
        $this->email = $email;
        $this->token = $token;
        $this->userType = $userType;
    }
}
