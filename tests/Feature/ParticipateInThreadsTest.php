<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
              ->post('/threads/some-channel/1/replies', [])
              ->assertRedirect('/login');
    }

    /** @test */

    public function an_authenticated_user_may_participate_in_forum_thread()
    {

        $this->signIn();

        // $this->be($user = User::factory()->create());

        $thread = Thread::factory()->create();

        $reply = Reply::factory()->make();

        $this->post($thread->path() .'/replies', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);

    }

     /** @test */
     public function a_reply_requires_a_body()
     {

         $this->withExceptionHandling()->signIn();

         $thread = Thread::factory()->create();

         $reply = Reply::factory()->make(['body' => null]);

         $this->post($thread->path() .'/replies', $reply->toArray())
               ->assertSessionHasErrors('body');
     }

         /** @test */
         public function unauthorized_users_cannot_delete_replies()
         {

             $this->withExceptionHandling();

             $reply = Reply::factory()->create();

             $this->delete("/replies/{$reply->id}")
                   ->assertRedirect('login');

             $this->signIn()
                   ->delete("/replies/{$reply->id}")
                   ->assertStatus(403);
         }

            /** @test */
            public function unauthorized_users_can_delete_replies()
            {
                $this->signIn();

                $reply = Reply::factory()->create(['user_id' => auth()->id()]);

                $this->delete("/replies/{$reply->id}")->assertStatus(302);

                $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

            }
}
