<?php

namespace App\Http\Controllers;

use App\Services\FeedInterestService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedInterestController extends Controller
{
    public function update(Request $request)
    {
        abort_unless(in_array($request->user()?->role, ['student', 'alumni'], true), 403);
        $data = $request->validate([
            'interests' => ['required', 'array', 'min:1', 'max:7'],
            'interests.*' => ['required', 'string', 'distinct', Rule::in(array_keys(FeedInterestService::OPTIONS))],
        ]);
        // Preference changes are always limited to the signed-in user.
        $request->user()->update(['interests' => array_values($data['interests'])]);

        return redirect()->route('newsfeed.index')->with('success', 'Your interests have been updated.');
    }
}
