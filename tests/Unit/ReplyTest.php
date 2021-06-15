<?php

namespace Tests\Unit;

use Tests\TestCase;
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
}

