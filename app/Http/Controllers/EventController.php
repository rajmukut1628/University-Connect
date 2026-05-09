<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $publicStatuses = ['active', 'published', 'approved'];

        $events = Event::with([
                'creator',
                'participants' => function ($query) {
                    $query->where('status', 'approved');
                },
            ])
            ->whereIn('status', $publicStatuses)
            ->latest('event_date')
            ->get();

        $registeredEvents = EventParticipant::where('user_id', auth()->id())
            ->pluck('status', 'event_id');

        $stats = [
            'total_events' => Event::whereIn('status', $publicStatuses)->count(),

            'upcoming_events' => Event::whereIn('status', $publicStatuses)
                ->where('event_date', '>=', now())
                ->count(),

            'my_registrations' => EventParticipant::where('user_id', auth()->id())->count(),

            'approved_participants' => EventParticipant::where('status', 'approved')->count(),

            'pending_requests' => EventParticipant::where('status', 'pending')->count(),
        ];

        return view('events.index', compact('events', 'registeredEvents', 'stats'));
    }

    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, ['admin', 'super_admin', 'alumni'])) {
            abort(403, 'UNAUTHORIZED ACCESS.');
        }

        return view('events.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, ['admin', 'super_admin', 'alumni'])) {
            abort(403, 'UNAUTHORIZED ACCESS.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],

            'start_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'end_time' => ['required'],

            'capacity' => ['nullable', 'integer', 'min:1'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['nullable', 'in:active,published,approved,draft,pending'],
        ]);

        $coverPath = null;

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('events/covers', 'public');
        }

        $status = auth()->user()->isAdmin()
            ? $request->input('status', 'active')
            : 'pending';

        $eventDateTime = $request->start_date . ' ' . $request->start_time;

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,

            'event_date' => $eventDateTime,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,

            'capacity' => $request->capacity,
            'cover_image' => $coverPath,
            'status' => $status,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('events.index')
            ->with(
                'success',
                auth()->user()->isAdmin()
                    ? 'Event created successfully.'
                    : 'Event submitted successfully. Waiting for admin approval.'
            );
    }

    public function register(Event $event)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, ['student', 'alumni'])) {
            return back()->withErrors([
                'event' => 'Only students and alumni can register for events.',
            ]);
        }

        if (!in_array($event->status, ['active', 'published', 'approved'])) {
            return back()->withErrors([
                'event' => 'This event is not open for registration.',
            ]);
        }

        $approvedCount = $event->participants()
            ->where('status', 'approved')
            ->count();

        if ($event->capacity && $approvedCount >= $event->capacity) {
            return back()->withErrors([
                'event' => 'This event is already full.',
            ]);
        }

        $existing = EventParticipant::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->withErrors([
                'event' => 'You have already submitted a registration request. Current status: ' . ucfirst($existing->status),
            ]);
        }

        EventParticipant::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Registration request submitted. Waiting for admin approval.');
    }

    public function pendingParticipants()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACCESS.');
        }

        $participants = EventParticipant::with(['event', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.events.pending-participants', compact('participants'));
    }

    public function approveParticipant(EventParticipant $participant)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACCESS.');
        }

        $event = $participant->event;

        $approvedCount = $event->participants()
            ->where('status', 'approved')
            ->count();

        if ($event->capacity && $approvedCount >= $event->capacity) {
            return back()->withErrors([
                'event' => 'Event capacity is already full.',
            ]);
        }

        $participant->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Participant approved successfully.');
    }

    public function rejectParticipant(EventParticipant $participant)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'UNAUTHORIZED ACCESS.');
        }

        $participant->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Participant rejected successfully.');
    }
}