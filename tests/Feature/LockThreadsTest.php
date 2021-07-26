<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

     /** @test */
     public function non_administration_may_not_lock_threads()
     {
         $this->withExceptionHandling();

         $this->signIn();

          $thread = Thread::factory()->create(['user_id' => auth()->id()]);

          $this->post(route('locked-threads.store', $thread))->assertStatus(403);

          $this->assertFalse(!! $thread->fresh()->locked);
     }


      /** @test */
      public function administrators_can_lock_threads()
      {
          $this->withExceptionHandling();

           $this->signIn();

           User::factory()->administrator()->create();

           $thread = Thread::factory()->create(['user_id' => auth()->id()]);

           $this->post(route('locked-threads.store', $thread));

           $this->assertFalse(!! $thread->fresh()->locked, 'Failed asserting that thread was locked');
      }


    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->withExceptionHandling();

        $this->signIn();

         $thread = Thread::factory()->create();

         $thread->lock();

         $this->post($thread->path() . '/replies', [
             'body' => 'Foobar',
             'user_id' => auth()->id()
         ])->assertStatus(302);
    }

}
