<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /** @test */
    public function a_user_can_search_threads()
    {
       config(['scout.driver' => 'algolia']);

       $search = 'foobar';

       Thread::factory()->count(2)->create([]);

       Thread::factory()->count(2)->create([
           'body' => "A thread with the {$search} term"
       ]);

       do {
           sleep(.25);
           $results = $this->getJson("/threads/search?q={$search}")->json()['data'];

       } while (empty($results));


       $this->assertCount(2, $results);

       Thread::latest()->take(4)->unsearchable();
    }

}
