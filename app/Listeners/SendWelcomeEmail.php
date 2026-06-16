<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail
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
        // Safely handle the event
        try {
            // Example: send email (simplified)
            Log::info('Welcome email sent to: ' . $event->user->email);
        } catch (\Throwable $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
}
