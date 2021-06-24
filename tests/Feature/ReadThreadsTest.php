<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;



class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->thread = Thread::factory()->create();
    }

    /** @test */
    public function aUserCanViewThreads()
    {
        // $this->withoutExceptionHandling();

        $this->get('/threads')

            ->assertSee($this->thread->title);

    }

     /** @test */
     public function aUserCanReadSingleThread()
     {

         $this->get($this->thread->path())

            ->assertSee($this->thread->title);

     }

      /** @test */
      public function a_user_can_read_replies_that_are_associated_with_a_thread()
      {

        $reply = Reply::factory()->create(['thread_id' =>  $this->thread->id]);

        $this->get($this->thread->path())
                ->assertSee($reply->body);

      }

         /** @test */
         public function a_user_can_filter_threads_according_to_a_channel()
         {
            $channel = Channel::factory()->create();

            $threadInChannel = Thread::factory()->create(['channel_id' => $channel->id]);

            $threadNotInChannel = Thread::factory()->create();


           $this->get('/threads/' .$channel->slug)
                   ->assertSee($threadInChannel->title)
                   ->assertDontSee($threadNotInChannel->title);


         }

          /** @test */
          public function a_user_can_filter_threads_by_any_username()
          {
           $this->signIn(User::create(['name' => 'JohnDoe']));

            $threadByJohn = Thread::factory()->create(['user_id' => auth()->id()]);
            $threadNotByJohn = Thread::factory()->create();

            $this->get('threads?by=JohnDoe')
                ->assertSee($threadByJohn->title)
                ->assertDontSee($threadNotByJohn->title);

             }
    }
