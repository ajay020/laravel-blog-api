<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends Controller {
    public function index () {
        return TagResource::collection(
            Tag::all()
        );
    }

    public function store (Request $request) {
        $tag = Tag::create($request->only('name'));

        return new TagResource($tag);
    }

    public function update (Request $request, Tag $tag) {
        // $tag->update($request->only('name'));

        $tag->name = $request->name;
        $tag->save();

        return new TagResource($tag);
    }

    public function destroy (Tag $tag) {
        // $tag->delete();
        Tag::destroy($tag->id);

        return response()->json([
            'message' => 'Tag deleted successfully'
        ]);
    }
}
