<?php

namespace App\Http\Controllers;

use App\Models\FeedComment;
use App\Models\FeedLike;
use App\Models\FeedShare;
use Illuminate\Http\Request;

class FeedActionController extends Controller
{
    public function like(Request $request)
    {
        $request->validate([
            'feedable_type' => ['required', 'string'],
            'feedable_id' => ['required', 'integer'],
        ]);

        $like = FeedLike::where('user_id', auth()->id())
            ->where('feedable_type', $request->feedable_type)
            ->where('feedable_id', $request->feedable_id)
            ->first();

        if ($like) {
            $like->delete();
            return back()->with('success', 'Like removed.');
        }

        FeedLike::create([
            'user_id' => auth()->id(),
            'feedable_type' => $request->feedable_type,
            'feedable_id' => $request->feedable_id,
        ]);

        return back()->with('success', 'Post liked.');
    }

    public function comment(Request $request)
    {
        $request->validate([
            'feedable_type' => ['required', 'string'],
            'feedable_id' => ['required', 'integer'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        FeedComment::create([
            'user_id' => auth()->id(),
            'feedable_type' => $request->feedable_type,
            'feedable_id' => $request->feedable_id,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function share(Request $request)
    {
        $request->validate([
            'feedable_type' => ['required', 'string'],
            'feedable_id' => ['required', 'integer'],
        ]);

        FeedShare::create([
            'user_id' => auth()->id(),
            'feedable_type' => $request->feedable_type,
            'feedable_id' => $request->feedable_id,
        ]);

        return back()->with('success', 'Post shared.');
    }
}