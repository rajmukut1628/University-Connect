@props(['user'])
@if($user->profile_image)
    <img src="{{ $user->getProfileImageUrl() }}" alt="{{ $user->name }}"
         data-profile-photo
         class="mx-auto rounded-3xl border-4 border-white shadow-2xl"
         style="display:block;width:auto;height:auto;max-width:100%;max-height:224px;object-fit:contain;"
    >
@else
    <div class="mx-auto flex h-32 w-32 items-center justify-center rounded-3xl bg-indigo-600 text-5xl font-black text-white shadow-2xl" aria-label="No profile picture">
        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
    </div>
@endif
