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
    public function an_administration_can_lock_any_thread()
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
