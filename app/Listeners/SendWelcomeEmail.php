<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        try {
             Mail::to($event->user->email)
                    ->send(new WelcomeMail($event->user));

            // Log::info('Welcome email sent to: ' . $event->user->email);
        } catch (\Throwable $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
}
