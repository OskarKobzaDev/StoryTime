<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMarkdownToHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    use ConvertsMarkdownToHtml;
    use Searchable;


    protected static function booted(): void{
        static::saving(fn (Post $model) => $model->fill(['html' => str($model->body)->markdown([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 3,
        ])]));
    }
    protected $fillable = [
        'body',
        'title',
        'topic_id',
        'user_id',
        'html'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function title(): Attribute
    {
        return Attribute::set(fn ($value) => Str::title($value));
    }

    public function showRoute(array $parameteres =[])
    {
        return route('posts.show', [$this, Str::slug($this->title), ...$parameteres]);
    }
}
