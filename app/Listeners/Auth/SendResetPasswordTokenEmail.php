<?php

namespace App\Listeners\Auth;

use App\Events\Auth\ResetPasswordTokenCreated;
use App\Services\CommandService;

class SendResetPasswordTokenEmail
{
    protected $commandService;

    /**
     * Create the event listener.
     * @param $commandService
     */
    public function __construct(CommandService $commandService)
    {
        $this->commandService = $commandService;
    }

    /**
     * Run command to send reset password token via email
     * @param  ResetPasswordTokenCreated $event
     */
    public function handle(ResetPasswordTokenCreated $event)
    {
        $email = $event->email;
        $token = $event->token;
        $type = $event->userType;

        $this->commandService
            ->runBackgroundCommand('email:send-reset-password-token', compact('email', 'token', 'type'));
    }
}
