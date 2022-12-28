<?php

namespace App\Services;

use App\Events\NewPost;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function createNewPost(array $data) : void
    {
        $post = Post::query()->create($data + [
            'user_id' => Auth::id(),
        ]);

        $this->sendNotification($post);
    }

    private function sendNotification(Post $post) : void
    {
        event(new NewPost($post));
    }

    public function updatePost(Post $post, array $data) : void
    {
        $post->update($data);
    }
}