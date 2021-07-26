<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_thread_creator_may_mark_any_reply_as_best_reply()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $replies = Reply::factory()->count(2)->create(['thread_id' => $thread->id]);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    function only_thread_creator_may_mark_a_reply_as_best()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $replies = Reply::factory()->count(2)->create(['thread_id' => $thread->id]);

        $this->signIn();

        User::factory()->create();

        $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());

    }

  /** @test */
  function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
  {

      $this->signIn();

      $reply = Reply::factory()->create(['user_id' => auth()->id()]);

      $reply->thread->$reply;

      $this->deleteJson(route('replies.destroy', $reply));

      $this->assertNull($reply->thread->fresh()->best_reply_id);

  }

}
