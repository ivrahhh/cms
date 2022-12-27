<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase; 
    
    public function test_user_can_verify_their_email()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->actingAs($user)->get($url);

        $this->assertTrue(
            $user->fresh()->hasVerifiedEmail(),
        );
    }

    public function test_the_verified_event_will_be_fired()
    {
        Event::fake(Verified::class);
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->actingAs($user)->get($url);

        Event::assertDispatched(Verified::class);
    }

    public function test_user_cannot_verify_their_email_with_expired_verification_link()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $url = URL::temporarySignedRoute('verification.verify', now()->subHour(), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $response = $this->actingAs($user)->get($url);
        $response->assertStatus(403);

        $this->assertFalse(
            $user->fresh()->hasVerifiedEmail(),
        );
    }
}
