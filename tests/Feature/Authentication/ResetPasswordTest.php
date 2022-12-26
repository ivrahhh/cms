<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public function test_user_can_reset_their_password()
    {
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->put(route('password.update'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertValid();

        $this->assertTrue(
            Hash::check('new_password', $user->fresh()->password),
        );
    }

    public function test_the_data_in_the_database_will_be_remove_after_reseting_the_password()
    {
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->put(route('password.update'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertValid();

        $this->assertDatabaseMissing('password_resets', [
            'email' => $user->email,
        ]);
    }

    public function test_it_will_fire_the_password_reset_event()
    {
        Event::fake(PasswordReset::class);
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->put(route('password.update'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertValid();
        Event::assertDispatched(PasswordReset::class);
    }

    public function test_it_will_throw_an_error_with_invalid_token()
    {
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->put(route('password.update'), [
            'email' => $user->email,
            'token' => 'invalidToken',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertInvalid('email');
    }

    public function test_it_will_throw_an_error_with_invalid_email()
    {
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->put(route('password.update'), [
            'email' => 'wrong_email@Email.com',
            'token' => $token,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertInvalid('email');
    }

    public function test_it_will_throw_a_validation_error_if_the_password_not_confirmed()
    {
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->put(route('password.update'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new_password',
            'password_confirmation' => '',
        ]);

        $response->assertInvalid('password');
    }
}
