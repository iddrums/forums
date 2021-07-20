<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $users = User::whereIn('name', $event->reply->mentionedUsers())->get();

        collect($event->reply->mentionedUsers())
              ->each(function ($user) use ( $event) {
                $user->notify(new YouWereMentioned($event->reply));
        });
    }
}
