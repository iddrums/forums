<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

      /** @test */
      public function it_has_an_owner()
      {
          $reply = Reply::factory()->create();

          $this->assertInstanceOf(Reply::class, $reply);
      }

     /** @test */
     public function it_knows_if_it_was_just_published()
     {
         $reply = Reply::factory()->create();

         $this->assertTrue($reply->wasJustPublished());

         $reply->created_at = Carbon::now()->subMonth();

         $this->assertFalse($reply->wasJustPublished());

     }

      /** @test */
      public function it_can_detect_all_mentioned_users_in_the_body()
      {
          $reply = Reply::factory()->create([
              'body' => '@Kante wants to talk to @Werner'
            ]);


          $this->assertEquals(['Kante', 'Werner'], $reply->mentionedUsers());

      }
}

