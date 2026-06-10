<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

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
                'tags',
            ])
            ->withCount('comments');

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
        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store('posts', 'public');
        }

        $post = Post::create([
            ...Arr::except(
                $request->validated(),
                ['tags']
            ),
            'user_id' => $request->user()->id,
            'image_path' => $imagePath,
        ]);

        $post->tags()->sync(
            $request->input('tags', [])
        );

        return response()->json(
            new PostResource(
                $post->load(['user', 'category'])
            ),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) {
        return new PostResource($post->load([
            'user',
            'category',
            'tags',
            'comments.user',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post) {
        $this->authorize('update', $post);

        $attributes = Arr::except(
            $request->validated(),
            ['tags']
        );

        if ($request->hasFile('image')) {

            if ($post->image_path) {
                Storage::disk('public')
                    ->delete($post->image_path);
            }

            $attributes['image_path'] = $request
                ->file('image')
                ->store('posts', 'public');
        }

        $post->update($attributes);

        $post->tags()->sync(
            $request->input('tags', [])
        );

        return response()->json(
             new PostResource(
                $post->load(['user', 'category'])
            ),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {
        $this->authorize('delete', $post);

        if($post->image_path) {
            Storage::disk('public')
            ->delete($post->image_path);
        }

        $post->delete($post->id);

        return response()->noContent();
    }
}
