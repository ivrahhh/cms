<?php

namespace App\Http\Controllers\Content;

use App\Events\NewPost;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\CreatePostRequest;
use App\Http\Requests\Content\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct(public PostService $service) {}

    public function store(CreatePostRequest $request) : RedirectResponse
    {
        $this->service->createNewPost($request->validated());

        return back()->with('status', 'New post has been created');
    }

    public function show(Post $post) : View
    {
        return view('pages.posts.view', compact('post'));
    }

    public function update(Post $post, UpdatePostRequest $request) : RedirectResponse
    {
        $this->service->updatePost($post, $request->validated());

        return back()->with([
            'status' => "The {$post->title} is successfully updated."
        ]);
    }
}
