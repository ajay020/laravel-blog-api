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
            
            'comments_count' => $this->comments_count,

            'comments' => CommentResource::collection(
                $this->whenLoaded('comments')
            ),

            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ],

            'created_at' => $this->created_at,

           
        ];
    }
}
