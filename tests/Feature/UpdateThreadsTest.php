<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();

        $this->signIn();
    }

    /** @test */
    public function unauthorized_users_may_not_update_threads()
    {
        $thread = Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test */
     public function a_thread_requires_a_title_and_body_to_be_updated()
     {

         $thread = Thread::factory()->create(['user_id' => auth()->id()]);

         $this->patch($thread->path(), [
           'title' => 'Changed',
         ])->assertSessionHasErrors('body');

         $this->patch($thread->path(), [
            'body' => 'Changed',
          ])->assertSessionHasErrors('title');
     }




      /** @test */
      public function a_thread_can_be_updated_by_its_creator()
      {

          $thread = Thread::factory()->create(['user_id' => auth()->id()]);

          $this->patchJson($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body',
          ]);

         tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Changed', $thread->title);
            $this->assertEquals('Changed body', $thread->body);
         });
      }

}
