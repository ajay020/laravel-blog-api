<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'published' => $this->published,

            'comments' => CommentResource::collection(
                $this->whenLoaded('comments')
            ),

            'comments_count' => $this->whenCounted(
                'comments'
            ),

            'author' => UserResource::make(
                $this->whenLoaded('user')
            ),

            // 'author' => [
            //     'id' => $this->user->id,
            //     'name' => $this->user->name,
            // ],

            'category' =>  CategoryResource::make(
                $this->whenLoaded('category')
            ),

            // 'category' => [
            //     'id' => $this->category->id,
            //     'name' => $this->category->name,
            //     'slug' => $this->category->slug,
            // ],

            'created_at' => $this->created_at,

            // 'tags' => $this->tags->map(fn ($tag) => [
            //     'id' => $tag->id,
            //     'name' => $tag->name,
            //     'slug' => $tag->slug,
            // ]),

            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),

            'image_url' => $this->image_path
                ? asset("storage/{$this->image_path}")
                : null,
        ];
    }
}
