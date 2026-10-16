<?php
namespace App\Http\Controllers;

use App\Services\DashboardActivityService as Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardActivityController extends Controller
{
    public function index(Request $request, string $section)
    {
        $user = $request->user();
        abort_unless($user && in_array($user->role, ['student', 'alumni'], true), 403);
        $pending = $section === 'mentorships' && $user->role === 'alumni' && $request->query('status') === 'pending';
        $title = match ($section) {
            'events' => 'My Registered Events',
            'mentorships' => $user->role === 'student' ? 'My Mentors' : ($pending ? 'My Pending Mentorship Requests' : 'My Mentees'),
            'unread' => 'Unread Messages',
            default => abort(404),
        };
        $items = match ($section) {
            'events' => Activity::events($user)->latest('event_date')->orderByDesc('id')->paginate(12),
            'mentorships' => Activity::connections($user, $pending)->orderBy('name')->orderBy('id')->paginate(12),
            'unread' => Activity::unread($user)->with('sender')->select('sender_id', DB::raw('COUNT(*) as unread_count'), DB::raw('MAX(created_at) as last_message_at'))->groupBy('sender_id')->orderByDesc('last_message_at')->paginate(12),
        };
        $items->withQueryString();
        return response()->view('dashboard.activity', compact('section', 'items', 'title', 'pending'))->header('Cache-Control', 'private, no-store');
    }
}
