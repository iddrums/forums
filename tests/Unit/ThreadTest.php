<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Support\Facades\Redis;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp(): void

    {
        parent::setUp();

        $this->thread = Thread::factory()->create();

    }

    /** @test */
    public function a_thread_has_a_path()
    {

        $thread = Thread::factory()->create();

        $this->assertEquals(
                 "/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }

    /** @test */
    public function a_thread_has_creator()
    {

        $this->assertInstanceOf(Thread::class, $this->thread);

    }

    /** @test */
    public function a_thread_has_replies()
    {

        $this->assertInstanceOf(Thread::class, $this->thread);

    }

        /** @test */
        public function a_thread_can_add_a_reply()
        {

            $this->thread->addReply([
               'body' => 'Foobar',
               'user_id' => 1
            ]);

            $this->assertCount(1, $this->thread->replies);

        }

        /** @test */
        public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
        {
            Notification::fake();

            $this->signIn()
                 ->thread
                 ->subscribe()
                 ->addReply([
                    'body' => 'Foobar',
                    'user_id' => 1
            ]);

            Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

        }

        /** @test */
        public function a_thread_belongs_to_a_channel()
        {
            $thread = Thread::factory()->create();

            $this->assertInstanceOf(Channel::class,  $thread->channel);

        }

       /** @test */
       public function a_thread_can_be_subscribed_to()
       {
           $thread = Thread::factory()->create();

           $thread->subscribe($userId = 1);

           $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
       }

        /** @test */
        public function a_thread_can_be_unsubscribed_from()
        {
            $thread = Thread::factory()->create();

            $thread->subscribe($userId = 1);

            $thread->unsubscribe($userId);

            $this->assertCount(0, $thread->subscriptions);
        }

         /** @test */
         public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
         {
             $thread = Thread::factory()->create();

             $this->signIn();

             $this->assertFalse($thread->isSubscribedTo);

             $thread->subscribe();

             $this->assertTrue($thread->isSubscribedTo);
         }

        /** @test */
        public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
        {
            $this->signIn();

            $thread = Thread::factory()->create();

            tap(auth()->user(), function($user) use ($thread) {

                $this->assertTrue($thread->hasUpdatesFor($user));

                 $user->read($thread);

                $this->assertFalse($thread->hasUpdatesFor($user));
            });
        }


}
