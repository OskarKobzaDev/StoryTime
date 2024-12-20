<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LikeController extends Controller
{
    public function store(Request $request, string $type, int $id)
    {
        $likeable = $this->findLikeable($type, $id);

        Gate::authorize('create', [Like::class, $likeable]);

        $likeable->likes()->create([
            'user_id' => $request->user()->id,
        ]);

        $likeable->increment('likes_count');

        return back();
    }

    public function destroy(Request $request, string $type, int $id)
    {
        $likeable = $this->findLikeable($type, $id);

        Gate::authorize('delete', [Like::class, $likeable]);

        $likeable->likes()->whereBelongsTo($request->user())->delete();

        $likeable->decrement('likes_count');
        //dd($likeable->likes_count);
        return back();
    }

    /**
     * @param string $type
     * @param int $id
     * @return mixed
     */
    protected function findLikeable(string $type, int $id): Model
    {
        /** @var class-string<Model>|null $modelName */

        $modelName = Relation::getMorphedModel($type);

        if ($modelName === null) {
            throw new ModelNotFoundException();
        }

        $likeable = $modelName::findOrFail($id);
        return $likeable;
    }
}
