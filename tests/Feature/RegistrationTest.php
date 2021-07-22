<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        event(new Registered(User::factory()->create()));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses()
    {
       $this->post('/register', [
           'name' => 'Pogba',
           'email' => 'pogba@example.com',
           'password' => '123456789',
           'password_confirmation' => '123456789'
       ]);

         $user =  User::whereName('Pogba')->first();

         $this->assertFalse($user->confirmed);
         $this->assertNotNull($user->confirmation_token);

         $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

         $this->assertTrue($user->fresh()->confirmed);

         $response->assertRedirect('/threads');

    }
}
