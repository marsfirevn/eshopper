<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $email;
    protected $token;
    protected $userType;

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
        $view = config('auth.passwords.' . trim(strtolower($type), 's') . 's.email');

        return $this->to($this->email)
            ->view($view, compact('email', 'token', 'type'))
            ->onConnection('redis')
            ->onQueue('emails');
    }
}
