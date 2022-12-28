<?php

namespace App\Listeners;

use App\Events\NewPost;
use App\Models\Subscription;
use App\Notifications\NewPostNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewPostNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewPost  $event
     * @return void
     */
    public function handle(NewPost $event)
    {
        $subscriber = Subscription::all();
        
        Notification::send($subscriber, new NewPostNotification($event->post));
    }
}
