<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\CommentAddedNotification;
use Illuminate\Support\Facades\Log;

class SendCommentNotification
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
    public function handle(CommentCreated $event): void
    {

        Log::info('Listener executed!');

        $comment = $event->comment;
        $post = $comment->post;

        if ($post->user->id !== $comment->user_id) {
            // $post->user->notify(new CommentAddedNotification($comment));

            try {
                $post->user->notify(new CommentAddedNotification($comment));
                Log::info('Notification sent successfully.');
            } catch (\Throwable $e) {
                Log::error($e->getMessage());
                Log::error($e->getTraceAsString());
            }
        }
    }
}
