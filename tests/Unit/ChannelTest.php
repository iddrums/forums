<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

      /** @test */
      public function a_channel_consist_of_threads()
      {
          $channel = Channel::factory()->create();

          $threads = Thread::factory()->create(['channel_id' => $channel->id]);


          $this->assertTrue($channel->threads->contains($threads));
      }
}
