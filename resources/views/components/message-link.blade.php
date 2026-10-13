@props(['user'])
@if(auth()->user()?->canMessage($user))
    <a href="{{ route('messages.show', $user) }}" class="rounded-2xl bg-indigo-600 px-6 py-3 font-bold text-white">Send Message</a>
@else
    <span aria-disabled="true" class="rounded-2xl border border-slate-500/30 px-6 py-3 text-sm text-slate-400">Messaging locked until mentorship is accepted</span>
@endif
