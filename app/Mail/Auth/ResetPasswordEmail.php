<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $token;
    protected $userType;
    protected $viewPath;

    /**
     * Create a new message instance.
     * @param string $email
     * @param string $token
     * @param string $userType
     */
    public function __construct($email, $token, $userType)
    {
        $this->email = $email;
        $this->token = $token;
        $this->userType = $userType;
        $this->viewPath = config('auth.passwords.' . trim(strtolower($userType), 's') . 's.email');
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build()
    {
        $email = $this->email;
        $token = $this->token;
        $type = $this->userType;

        return $this->to($this->email)->view($this->viewPath, compact('email', 'token', 'type'));
    }
}
