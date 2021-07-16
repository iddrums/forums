<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Thread;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = Thread::factory()->create();

        $this->post($thread->path() . '/subscriptions');

        $thread->addReply([
           'user_id' => auth()->id(),
           'body' => 'Some reply here'
        ]);

        // $this->assertCount(1, $thread->subscriptions);
    }

     /** @test */
     public function a_user_can_unsubscribe_from_threads()
     {
         $this->signIn();

         $thread = Thread::factory()->create();

         $thread->subscribe();

         $this->delete($thread->path() . '/subscriptions');

         $this->assertCount(0, $thread->subscriptions);
     }

}
