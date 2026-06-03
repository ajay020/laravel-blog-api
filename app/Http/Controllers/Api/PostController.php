<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return PostResource::collection(
            Post::with([
                'user', 
                'category'
            ])->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request) {
        $post = Post::create([
            ...$request->validated(),
            'user_id' => 1,
        ]);

        return new PostResource(
            $post->load(['user', 'category'])
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post){
        return new PostResource($post->load([
            'user',
            'category'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
  public function update( UpdatePostRequest $request, Post $post){
        $post->update($request->validated());

        return new PostResource(
            $post->load([
                'user',
                'category',
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
  public function destroy(Post $post) {
        $post->delete($post->id);

        return response()->noContent();
    }
}
