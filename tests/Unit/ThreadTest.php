<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Thread;
use App\Models\Channel;
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
    public function a_thread_can_make_a_string_path()
    {

        $thread = Thread::factory()->create();

        $this->assertEquals(
                 "/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
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
         public function a_thread_belongs_to_a_channel()
         {
             $thread = Thread::factory()->create();

             $this->assertInstanceOf(Channel::class,  $thread->channel);                          
 
         }
}
