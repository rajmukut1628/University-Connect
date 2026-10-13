<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 text-white space-y-6">
        <h1 class="text-2xl font-bold">Archive & Date Extension Requests</h1>
        <div class="flex gap-4">@foreach(['job','event','donation'] as $section)<a class="rounded-lg p-3 {{ $type === $section ? 'bg-indigo-600' : 'bg-slate-800' }}" href="{{ route('posts.archive',['type'=>$section]) }}">{{ ucfirst($section) }}</a>@endforeach</div>
        @if(session('success')) <p class="text-green-400">{{ session('success') }}</p> @endif
        @if($errors->any()) <p class="text-red-400">{{ $errors->first() }}</p> @endif
        @if(!$management)
        <h2 class="text-xl font-bold">Archived Posts</h2>
        @forelse($posts as $post)
            <div class="rounded-xl bg-slate-800 p-4"><a href="{{ route(match($type){'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'},$post) }}">{{ $post->title }}</a><p>Archived: {{ $post->archived_at }}</p></div>
        @empty <p>No archived posts.</p> @endforelse
        {{ $posts->links() }}
        @endif
        <h2 class="text-xl font-bold">{{ $management ? 'Section Requests' : 'My Requests' }}</h2>
        @forelse($requests as $extension)
            <div class="rounded-xl bg-slate-800 p-4 space-y-3">
                <p>{{ $extension->user?->name }} · {{ ucfirst($extension->status) }} · Post #{{ $extension->post_id }}</p>
                <a href="{{ route(match($type){'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'},$extension->post_id) }}">View Post</a>
                <p class="whitespace-pre-line">{{ $extension->reason }}</p>
                @foreach($extension->dates as $field=>$value)<p>{{ ucwords(str_replace('_',' ', $field)) }}: {{ $value }}</p>@endforeach
                @if($extension->review_note)<p>Admin note: {{ $extension->review_note }}</p>@endif
                @if($management && $extension->status === 'pending')
                    <form method="POST" action="{{ route('posts.extension.review',$extension) }}" class="flex flex-wrap gap-3">@csrf @method('PATCH')<input name="review_note" maxlength="3000" placeholder="Review note (required to reject)" class="bg-slate-900 rounded-lg p-2"/><button name="decision" value="approved" class="bg-green-700 rounded-lg px-4 py-2">Approve & Publish</button><button name="decision" value="rejected" class="bg-red-700 rounded-lg px-4 py-2">Reject</button></form>
                @endif
            </div>
        @empty <p>No extension requests.</p> @endforelse
        {{ $requests->links() }}
    </div>
</x-app-layout>
