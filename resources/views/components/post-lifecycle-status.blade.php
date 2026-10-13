@props(['post'])
@if(session('success'))
    <div role="status" class="my-4 rounded-xl border border-green-500/30 bg-green-500/10 p-4 text-green-300">{{ session('success') }}</div>
@endif
@php $expiry = \App\Services\PostLifecycleService::expiry($post); @endphp
<div class="my-3 text-sm text-slate-300">
    Status: {{ \App\Services\PostLifecycleService::accepting($post) ? 'Active' : ($post->archived_at ? 'Archived' : 'Expired') }}
    @if($expiry) &middot; Expires: {{ $expiry->format('d M Y, h:i A') }} @endif
</div>
@if(!\App\Services\PostLifecycleService::accepting($post))
    <div class="my-4 rounded-xl border border-amber-500/30 bg-amber-500/10 p-4 text-amber-200">
        This post is archived or expired. Applications, registration and new payments are closed. The owner can request a date extension for admin approval.
    </div>
@endif
