<?php

namespace Tests\Feature;


use App\Trending;
use Tests\TestCase;
use App\Models\Thread;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class TrendingThreadsTest extends TestCase
{
    use  DatabaseMigrations;

    protected function setUp(): void
    {
    parent::setUp();

    $this->trending = new Trending();

    $this->trending->reset();

    }


    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = Thread::factory()->create();

        $this->call('GET', $thread->path());

        $this->assertCount(1, $trending =  $this->trending->get());

        $this->assertEquals($thread->title, $trending[0]->title);
    }

}
