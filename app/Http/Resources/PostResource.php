<?php

namespace App\Http\Resources;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    private bool $withLikePermission=false;
    public function withLikePermission(): self{
        $this->withLikePermission = true;
        return $this;
    }
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user'=> UserResource::make($this->whenLoaded('user')),
            'topic'=> TopicResource::make($this->whenLoaded('topic')),
            'title'=>$this->title,
            'body'=>$this->body,
            'html'=>$this->html,
            'likes_count' => $this->likes_count,
            'updated_at'=>$this->updated_at,
            'created_at'=>$this->created_at,
            'routes'=>[
                'show' => $this->showRoute(),
            ],
            'can'=>[
                'like' => $this->when($this->withLikePermission, fn() => $request->user()?->can('create', [Like::class, $this->resource])),
                'delete' => $request->user()?->can('delete',$this->resource),
                'update' => $request->user()?->can('update',$this->resource),
            ],
        ];
    }
}
