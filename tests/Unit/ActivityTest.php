<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

      /** @test */
      public function it_records_activity_when_a_thread_is_created()
      {
          $this->signIn();

          $thread = Thread::factory()->create();

          $this->assertDatabaseHas('activities', [
              'type' => 'created_thread',
              'user_id' => auth()->id(),
              'subject_id' => $thread->id,
              'subject_type' => 'App\Models\Thread'
          ]);

          $activity = Activity::first();

          $this->assertEquals($activity->subject->id, $thread->id);
      }

    /** @test */
    function it_records_activity_when_a_reply_is_created()
    {
       $this->signIn();

       $reply = Reply::factory()->create();

       $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        // Thread::factory()->create(['user_id' => auth()->id()]);

        Thread::factory()->hasCreator(['user_id' => auth()->id()])->count(2)->create();


        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));


    }
}
