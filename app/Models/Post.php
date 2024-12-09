<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMarkdownToHtml;
use App\Models\Concerns\ConvertsMatkdownToHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    use ConvertsMarkdownToHtml;

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
        'user_id',
        'html'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
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
