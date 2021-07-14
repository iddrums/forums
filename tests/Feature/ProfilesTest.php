<?php

namespace Tests\Feature;


use Tests\TestCase;

use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = User::factory()->create();

        $this->get("/profiles/{$user->name}")
             ->assertSee($user->name);
    }


    /** @test */
    public function profile_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $this->get("/profiles/" . auth()->user()->name)
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}
