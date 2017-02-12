<?php

namespace App\Console\Commands;

use App\Mail\Auth\ResetPasswordEmail;
use Illuminate\Console\Command;
use Mail;

class SendResetPasswordToken extends Command
{
    protected $description = 'Send reset password token';
    protected $signature = 'email:send-reset-password-token
        {email : Email for send token}
        {token : Token}
        {type : User type}
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $token = $this->argument('token');
        $userType = $this->argument('type');

        Mail::send(new ResetPasswordEmail($email, $token, $userType));
    }
}
