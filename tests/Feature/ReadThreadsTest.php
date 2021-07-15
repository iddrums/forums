<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Model;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->thread = Thread::factory()->create();
    }

    /** @test */
    public function aUserCanViewThreads()
    {
        // $this->withoutExceptionHandling();

        $this->get('/threads')

            ->assertSee($this->thread->title);

    }

    /** @test */
    public function aUserCanReadSingleThread()
    {

        $this->get($this->thread->path())

            ->assertSee($this->thread->title);

    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = Channel::factory()->create();

        $threadInChannel = Thread::factory()->create(['channel_id' => $channel->id]);

        $threadNotInChannel = Thread::factory()->create();


        $this->get('/threads/' .$channel->slug)
                ->assertSee($threadInChannel->title)
                ->assertDontSee($threadNotInChannel->title);


    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $user = User::factory()->create(['name' => 'Yinka']);
        $this->signIn($user);

        $threadByYinka = Thread::factory()->create(['user_id' => auth()->id()]);
        $threadNotByYinka = Thread::factory()->create();

        $this->get('threads?by=Yinka')
            ->assertSee($threadByYinka->title)
            ->assertDontSee($threadNotByYinka->title);

            }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReply = Thread::factory()
            ->hasReplies(2)
            ->create();

        $threadWithThreeReplies = Thread::factory()
            ->hasReplies(3)
            ->create();

        $threadWithOneReply = Thread::factory()
            ->hasReplies(1)
            ->create();

        $threadWithNoReplies = $this->thread;

        $response  = $this->getJson('/threads?popular=1')->json();

        $this->assertEquals([3, 2, 1, 0], array_column($response, 'replies_count'));

    }

    /** @test */
    public function a_user_can_filter_threads_by_those_that_unanswered()
    {
        $thread = Thread::factory()->create();

        Reply::factory()->create(['thread_id' => $thread->id]);

        $response  = $this->getJson('/threads?unanswered=1')->json();

        $this->assertCount(2, $response);

    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = Thread::factory()->create();

        Reply::factory()->hasThread(['thread_id' => $thread->id])->count(2)->create();

        $response  = $this->getJson($thread->path() . '/replies')->json();
        
        $this->assertCount(0, $response['data']);
        $this->assertEquals(0, $response['total']);

    }
}
