<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;


    // /**
    //  * A basic feature test example.
    //  *
    //  * @return void
    //  */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);

    // }

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();

    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {

        $thread = Thread::factory()->create()->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
           'user_id' => auth()->id(),
           'body' => 'Some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => User::factory()->create()->id,
            'body' => 'Some reply here'
         ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);

    }

      /** @test */
      public function a_user_can_fetch_their_unread_notifications()
      {
        //  DatabaseNotification::factory()->create();

          $thread = Thread::factory()->create()->subscribe();

          $thread->addReply([
              'user_id' =>  User::factory()->create()->id,
              'body' => 'Some reply here'
           ]);

          $user = auth()->user();

          $response = $this->getJson("/profiles/{$user->name}/notifications")->json();

          $this->assertCount(1, $response);

      }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {

        $thread = Thread::factory()->create()->subscribe();

        $thread->addReply([
            'user_id' =>  User::factory()->create()->id,
            'body' => 'Some reply here'
         ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);

    }
}

