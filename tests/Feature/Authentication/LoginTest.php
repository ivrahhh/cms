<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_if_the_request_is_rate_limited()
    {
        $user = User::factory()->create();

        for($x = 1; $x <= 6; $x++) {
            $response = $this->post(route('authenticate'), [
                'email' => $user->email,
                'password' => 'wrong_password',
            ]);
        }

        $response->assertInvalid('email');
        
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create();

        $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $this->assertGuest();
    }

    public function test_it_will_throw_a_validation_error_with_invalid_email_format()
    {
        $response = $this->post(route('authenticate'), [
            'email' => 'invalidEmailFormat',
            'password' => 'password',
        ]);

        $response->assertInvalid('email');
    }

    public function test_it_will_throw_a_validation_error_if_the_email_is_empty()
    {
        $response = $this->post(route('authenticate'), [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertInvalid('email');
    }

    public function test_it_will_throw_a_validation_error_if_the_password_is_empty()
    {
        $response = $this->post(route('authenticate'), [
            'email' => 'example@email.com',
            'password' => '',
        ]);

        $response->assertInvalid('password');
    }
}
