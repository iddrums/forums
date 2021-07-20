<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        $this->withExceptionHandling();

        $werner = User::factory()->create(['name' => 'Werner']);

        $this->signIn($werner);

        $kante = User::factory()->create(['name' => 'Kante']);

        $thread = Thread::factory()->create();

        $reply = Reply::factory()->make([
            'body' => '@Kante look at this'
        ]);

        $this->json('post', $thread->path() .'/replies', $reply->toArray());

        $this->assertCount(0, $werner->notifications);
    }

      /** @test */
      function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
      {

          User::factory()->create(['name' => 'oluwakemi']);

          User::factory()->create(['name' => 'oluwafemi']);

          User::factory()->create(['name' => 'oluwafeyi']);

          $results = $this->json('GET', '/api/users', ['name' => 'oluwa']);

          $this->assertCount(3, $results->json());

      }
}
