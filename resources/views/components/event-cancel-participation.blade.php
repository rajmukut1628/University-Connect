@props(['event', 'status'])
@if(in_array(auth()->user()->role, ['student', 'alumni'], true) && (int) $event->created_by !== (int) auth()->id() && in_array($status, ['pending', 'approved'], true))
    <form method="POST" action="{{ route('events.participation.cancel', $event) }}" class="mt-3" onsubmit="return confirm('Cancel your participation in this event?')">
        @csrf @method('DELETE')
        <button type="submit" class="w-full rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 font-bold text-red-400">Cancel Participation</button>
    </form>
@endif
