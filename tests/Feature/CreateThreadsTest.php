<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

      /** @test */
    public function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
                ->assertRedirect('/login');
    }


    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = Thread::factory()->make();

        $response = $this->post('/threads', $thread->toArray());

            $this->get($response->headers->get('Location'))
                ->assertSee($thread->title)
                ->assertSee($thread->body);

  }


    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');

    }


    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');

    }


    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        Channel::factory()->count(2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999999])
            ->assertSessionHasErrors('channel_id');

    }


    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {

        $this->withExceptionHandling();

        $thread = Thread::factory()->create();

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);

    }

    /** @test */
    public function authorized_users_may_not_delete_threads()
    {
        $this->signIn();

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $reply = Reply::factory()->create(['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', $thread->toArray());
        $this->assertDatabaseMissing('replies', $reply->toArray());


    }


  public function publishThread($overrides = [])
  {
       $this->withExceptionHandling()->signIn();

       $thread = Thread::factory()->make($overrides);

       return $this->post('/threads', $thread->toArray());
  }
}
