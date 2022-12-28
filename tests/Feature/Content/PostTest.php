<?php

namespace Tests\Feature\Content;

use App\Events\NewPost;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\NewPostNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class PostTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $creator;

    private User $editor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::factory()->create(['role' => 'Admin']);
        $this->creator = User::factory()->create(['role' => 'Creator']);
        $this->editor = User::factory()->create(['role' => 'Editor']);
    }

    /** Create Post Tests */

    public function test_admin_can_create_new_post()
    {
        $postData = [
            'title' => fake()->sentence(),
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->admin)->post(route('posts.store'), $postData);
        $response->assertValid();

        $this->assertDatabaseHas('posts', $postData);
    }

    public function test_creator_can_create_new_post()
    {
        $postData = [
            'title' => fake()->sentence(),
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->creator)->post(route('posts.store'), $postData);
        $response->assertValid();

        $this->assertDatabaseHas('posts', $postData);
    }

    public function test_editor_cannot_create_new_post()
    {
        $postData = [
            'title' => fake()->sentence(),
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->editor)->post(route('posts.store'), $postData);
        $response->assertStatus(403);
    }

    public function test_it_will_fire_a_new_post_event_when_admin_created_the_post()
    {
        Event::fake(NewPost::class);

        $postData = [
            'title' => fake()->sentence(),
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->admin)->post(route('posts.store'), $postData);
        $response->assertValid();

        Event::assertDispatched(NewPost::class);
    }

    public function test_it_will_fire_a_new_post_event_when_creator_created_the_post()
    {
        Event::fake(NewPost::class);

        $postData = [
            'title' => fake()->sentence(),
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->creator)->post(route('posts.store'), $postData);
        $response->assertValid();

        Event::assertDispatched(NewPost::class);
    }

    public function test_notification_will_be_sent_to_the_subscriber_when_admin_created_the_post()
    {
        Notification::fake(NewPostNotification::class);

        $postData = [
            'title' => fake()->sentence(),
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->admin)->post(route('posts.store'), $postData);
        $response->assertValid();

        Notification::assertSentTo(Subscription::all(), NewPostNotification::class);
    }

    public function test_notification_will_be_sent_to_the_subscriber_when_creator_created_the_post()
    {
        Notification::fake(NewPostNotification::class);

        $postData = [
            'title' => fake()->sentence(),
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->creator)->post(route('posts.store'), $postData);
        $response->assertValid();

        Notification::assertSentTo(Subscription::all(), NewPostNotification::class);
    }

    public function test_admin_cannot_create_new_post_with_no_title()
    {
        $postData = [
            'title' => '',
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->admin)->post(route('posts.store'), $postData);
        $response->assertInvalid('title');   
    }

    public function test_creator_cannot_create_new_post_with_no_title()
    {
        $postData = [
            'title' => '',
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->creator)->post(route('posts.store'), $postData);
        $response->assertInvalid('title');   
    }

    public function test_admin_cannot_create_a_new_post_with_not_unique_title()
    {
        $post = Post::factory()->create();

        $postData = [
            'title' => $post->title,
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->admin)->post(route('posts.store'), $postData);
        $response->assertInvalid('title');
    }

    public function test_creator_cannot_create_a_new_post_with_not_unique_title()
    {
        $post = Post::factory()->create();

        $postData = [
            'title' => $post->title,
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->creator)->post(route('posts.store'), $postData);
        $response->assertInvalid('title');
    }

    /** Update Post Tests */

    public function test_admin_can_update_the_post_title()
    {
        $post = Post::factory()->create();

        $postUpdated = [
            'title' => 'New Post Title',
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->admin)->put(route('posts.update', $post->id), $postUpdated);
        $response->assertValid();

        $this->assertSame($post->fresh()->title, $postUpdated['title']);
    }

    public function test_editor_can_update_the_post_title()
    {
        $post = Post::factory()->create();

        $postUpdated = [
            'title' => 'New Post Title',
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->editor)->put(route('posts.update', $post->id), $postUpdated);
        $response->assertValid();

        $this->assertSame($post->fresh()->title, $postUpdated['title']);
    }

    public function test_creator_can_update_the_post_title()
    {
        $post = Post::factory()->create();

        $postUpdated = [
            'title' => 'New Post Title',
            'content' => fake()->realText(3000),
        ];

        $response = $this->actingAs($this->creator)->put(route('posts.update', $post->id), $postUpdated);
        $response->assertValid();

        $this->assertSame($post->fresh()->title, $postUpdated['title']);
    }
}
