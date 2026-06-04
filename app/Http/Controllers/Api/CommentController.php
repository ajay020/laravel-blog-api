<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
    Post $post // Route Model Binding
    ) {
        return CommentResource::collection(
            $post->comments()
                ->with('user')
                ->latest()
                ->paginate(10)
                ->withQueryString()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreCommentRequest $request,
        Post $post // Route Model Binding
    ) {
        $comment = $post->comments()->create([
            'body' => $request->body,
            'user_id' => $request->user()->id,
        ]);

        return new CommentResource(
            $comment->load('user')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateCommentRequest $request,
        Comment $comment
    ) {
        $this->authorize('update', $comment);

        $comment->update([
            'body' => $request->body,
        ]);

        return new CommentResource(
            $comment->load('user')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment) { // Route Model Binding
        $this->authorize('delete', $comment);

        $comment->delete($comment->id);

        return response()->noContent();
    }
}
