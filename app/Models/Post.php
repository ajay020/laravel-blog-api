<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder; 



class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'published',
        'user_id',
        'category_id',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope to filter posts
     *  
     * */ 

    #[Scope]
    protected function published(Builder $query ): void {
        $query->where('published', true);
    }

    #[Scope]
    protected function search(Builder $query, ?string $search ): void {
        if ($search) {
            // $query->where(
            //     'title',
            //     'like',
            //     "%{$search}%"
            // );

            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }
    }

    #[Scope]
    protected function inCategory(Builder $query, ?int $categoryId ): void {
        if ($categoryId) {
            $query->where('category_id', $categoryId );
        }
    }

    #[Scope]
    protected function sort( Builder $query, ?string $sort ): void {

        if ($sort === 'oldest') {
            $query->oldest();
            return;
        }

        $query->latest();
    }

    #[Scope]
    protected function tag( Builder $query, ?int $tagId ): void {
        if ($tagId) {
            $query->whereHas('tags', function ($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            });
        }
    }
}
