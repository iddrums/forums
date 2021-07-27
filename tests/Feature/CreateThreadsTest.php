<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Channel;
use App\Models\Activity;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        app()->singleton(Recaptcha::class,  function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });

            return $m;

        });
    }

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
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
    // $user = User::factory()->state('unverified')->create();
    $user = User::factory()->unconfirmed()->create();

    $this->withExceptionHandling()->signIn($user);

    $thread = Thread::factory()->make();

    return $this->post('/threads', $thread->toArray())
                ->assertRedirect('/threads')
                ->assertSessionHas('flash', 'You must first confirm your email address');
    }


    /** @test */
    public function a_user_can_create_new_forum_threads()
    {
        $response = $this->publishThread(['title' => 'Some Title', 'body' => 'Some body']);

            $this->get($response->headers->get('Location'))
                ->assertSee('Some Title')
                ->assertSee('Some body');

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
   public function a_thread_requires_recaptcha_verification()
   {
       unset(app()[Recaptcha::class]);

       $this->publishThread(['g-recaptcha-response' => 'test'])
           ->assertSessionHasErrors('g-recaptcha-response');

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
      public function a_thread_requires_a_unique_slug()
      {
          $this->signIn();

          $thread = Thread::factory()->create(['title' => 'Foo Title']);

          $this->assertEquals($thread->fresh()->slug, 'foo-title');

          $thread = $this->postJson('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

          $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);

      }


      /** @test */
      public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
      {
          $this->signIn();

          $thread = Thread::factory()->create(['title' => 'Some Title 24']);

          $thread = $this->postJson('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

          $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);

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

        $this->assertEquals(2, Activity::count());
    }


  public function publishThread($overrides = [])
  {
       $this->withExceptionHandling()->signIn();

       $thread = Thread::factory()->make($overrides);

       return $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
  }
}
