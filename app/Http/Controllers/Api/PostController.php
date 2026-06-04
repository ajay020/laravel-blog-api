<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $query = Post::query()
            ->with([
                'user',
                'category',
            ]);

        if ($search = request('search')) {
            $query->where(
                'title',
                'like',
                "%{$search}%"
            );
        }

        if ($category = request('category')) {
            $query->where(
                'category_id',
                $category
            );
        }

        if (request('sort') === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        return PostResource::collection(
              $query->paginate(10)->withQueryString()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request) {
        $post = Post::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
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
    public function update( UpdatePostRequest $request, Post $post) {

        $this->authorize('update', $post);

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

        $this->authorize('delete', $post);

        $post->delete($post->id);

        return response()->noContent();
    }
}
