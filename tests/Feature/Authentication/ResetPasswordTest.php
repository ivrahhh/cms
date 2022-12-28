<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private PasswordBroker $broker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->broker = app('auth.password.broker');

        info('setUp() triggered');
    }

    public function test_user_can_reset_their_password()
    {
        $response = $this->put(route('password.update'), [
            'email' => $this->user->email,
            'token' => $this->broker->createToken($this->user),
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertValid();

        $this->assertTrue(
            Hash::check('new_password', $this->user->fresh()->password),
        );
    }

    public function test_the_data_in_the_database_will_be_remove_after_reseting_the_password()
    {
        $response = $this->put(route('password.update'), [
            'email' => $this->user->email,
            'token' => $this->broker->createToken($this->user),
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertValid();

        $this->assertDatabaseMissing('password_resets', [
            'email' => $this->user->email,
        ]);
    }

    public function test_it_will_fire_the_password_reset_event()
    {
        Event::fake(PasswordReset::class);

        $response = $this->put(route('password.update'), [
            'email' => $this->user->email,
            'token' => $this->broker->createToken($this->user),
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertValid();
        Event::assertDispatched(PasswordReset::class);
    }

    public function test_it_will_throw_an_error_with_invalid_token()
    {
        $validToken = $this->broker->createToken($this->user);
     
        $response = $this->put(route('password.update'), [
            'email' => $this->user->email,
            'token' => 'invalidToken',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertInvalid('email');
    }

    public function test_it_will_throw_an_error_with_invalid_email()
    {
        $response = $this->put(route('password.update'), [
            'email' => 'wrong_email@Email.com',
            'token' => $this->broker->createToken($this->user),
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertInvalid('email');
    }

    public function test_it_will_throw_a_validation_error_if_the_password_not_confirmed()
    {
        $response = $this->put(route('password.update'), [
            'email' => $this->user->email,
            'token' => $this->broker->createToken($this->user),
            'password' => 'new_password',
            'password_confirmation' => '',
        ]);

        $response->assertInvalid('password');
    }
}
