<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public function test_user_can_request_a_password_reset_link()
    {
        $user = User::factory()->create();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertValid();

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);
    }

    public function test_the_reset_password_notification_will_be_sent()
    {
        Notification::fake(ResetPassword::class);
        $user = User::factory()->create();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertValid();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_it_will_throw_a_validation_error_with_invalid_email_format()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'invalidEmailFormat'
        ]);

        $response->assertInvalid('email');
    }

    public function test_it_will_throw_a_validation_error_if_the_email_is_not_registered()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'unregistered@email.com'
        ]);

        $response->assertInvalid('email');
    }
}
