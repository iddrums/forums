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

        $this->post(route('register'), [
            'name' => 'Pogba',
            'email' => 'pogba@example.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses()
    {
        Mail::fake();

       $this->post(route('register'), [
           'name' => 'Pogba',
           'email' => 'pogba@example.com',
           'password' => '123456789',
           'password_confirmation' => '123456789'
       ]);

         $user =  User::whereName('Pogba')->first();

         $this->assertFalse($user->confirmed);
         $this->assertNotNull($user->confirmation_token);

         $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
               ->assertRedirect('/threads');

         $this->assertTrue($user->fresh()->confirmed);

    }

    /** @test */
    function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
             ->assertRedirect('/threads')
             ->assertSessionHas('flash', 'Unknown token');

    }
}
