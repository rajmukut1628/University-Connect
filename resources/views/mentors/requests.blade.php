<x-app-layout>

    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-violet-950 to-fuchsia-950 p-8 shadow-2xl border border-white/10">

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(139,92,246,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div>

                    <p class="text-sm uppercase tracking-[0.35em] text-fuchsia-300 font-black">
                        Mentorship Control
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        Student Requests
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Review mentorship requests from verified students.
                        Accept suitable requests, reject them with a reason,
                        and complete accepted mentorships when the mentoring journey is finished.
                    </p>

               <a
    href="{{ route('alumni.dashboard') }}"
    class="px-6 py-4 rounded-2xl bg-white/10 text-white font-black border border-white/10 hover:bg-white/20 transition"
>
    <i class="fas fa-arrow-left mr-2"></i>
    Back to Dashboard
</a>

        </div>

    </x-slot>


    <style>

        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, .16);

            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, .16),
                rgba(255, 255, 255, .06)
            );

            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15, 23, 42, .18);
            transition: .35s ease;
        }

        .uc-card:hover {
            transform: translateY(-4px);
        }

        .uc-action-card {
            border-radius: 1rem;
            transition: .25s ease;
        }

        .uc-action-card:hover {
            transform: translateY(-2px);
        }

    </style>


    <div class="space-y-8">


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-start gap-3 text-emerald-600">

                    <div class="h-10 w-10 shrink-0 rounded-xl bg-emerald-500/15 flex items-center justify-center">

                        <i class="fas fa-circle-check"></i>

                    </div>

                    <div>

                        <p class="font-black">
                            Success
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-start gap-3 text-red-600">

                    <div class="h-10 w-10 shrink-0 rounded-xl bg-red-500/15 flex items-center justify-center">

                        <i class="fas fa-circle-exclamation"></i>

                    </div>

                    <div>

                        <p class="font-black">
                            Action Failed
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            {{ session('error') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="uc-card p-5">

                <div class="relative z-10">

                    <p class="font-black text-red-500 mb-3">

                        <i class="fas fa-triangle-exclamation mr-2"></i>

                        Please check the following:

                    </p>

                    <ul class="space-y-1 text-sm text-red-500">

                        @foreach($errors->all() as $error)

                            <li>
                                • {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- STATISTICS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">


            {{-- TOTAL --}}

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-center justify-between">

                    <div>

                        <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                            Total
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-purple-500">
                            {{ $requests->count() }}
                        </h3>

                    </div>

                    <div class="h-12 w-12 rounded-2xl bg-purple-500/15 text-purple-500 flex items-center justify-center">

                        <i class="fas fa-layer-group"></i>

                    </div>

                </div>

            </div>


            {{-- PENDING --}}

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-center justify-between">

                    <div>

                        <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                            Pending
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-amber-500">
                            {{ $requests->where('status', 'pending')->count() }}
                        </h3>

                    </div>

                    <div class="h-12 w-12 rounded-2xl bg-amber-500/15 text-amber-500 flex items-center justify-center">

                        <i class="fas fa-clock"></i>

                    </div>

                </div>

            </div>


            {{-- ACCEPTED --}}

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-center justify-between">

                    <div>

                        <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                            Accepted
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-emerald-500">
                            {{ $requests->where('status', 'accepted')->count() }}
                        </h3>

                    </div>

                    <div class="h-12 w-12 rounded-2xl bg-emerald-500/15 text-emerald-500 flex items-center justify-center">

                        <i class="fas fa-circle-check"></i>

                    </div>

                </div>

            </div>


            {{-- COMPLETED --}}

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-center justify-between">

                    <div>

                        <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                            Completed
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-blue-500">
                            {{ $requests->where('status', 'completed')->count() }}
                        </h3>

                    </div>

                    <div class="h-12 w-12 rounded-2xl bg-blue-500/15 text-blue-500 flex items-center justify-center">

                        <i class="fas fa-flag-checkered"></i>

                    </div>

                </div>

            </div>


            {{-- REJECTED --}}

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-center justify-between">

                    <div>

                        <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                            Rejected
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-red-500">
                            {{ $requests->where('status', 'rejected')->count() }}
                        </h3>

                    </div>

                    <div class="h-12 w-12 rounded-2xl bg-red-500/15 text-red-500 flex items-center justify-center">

                        <i class="fas fa-circle-xmark"></i>

                    </div>

                </div>

            </div>


        </div>


        {{-- ========================================================= --}}
        {{-- REQUEST LIST --}}
        {{-- ========================================================= --}}

        <div class="space-y-6">

            @forelse($requests as $requestItem)


                <div class="uc-card p-6">

                    <div class="relative z-10 flex flex-col xl:flex-row xl:items-start xl:justify-between gap-7">


                        {{-- ================================================= --}}
                        {{-- LEFT SIDE --}}
                        {{-- ================================================= --}}

                        <div class="flex-1 min-w-0">


                            {{-- STUDENT PROFILE --}}

                            <div class="flex flex-col sm:flex-row items-start gap-5">


                                {{-- PROFILE IMAGE --}}

                                @if($requestItem->student?->profile_image)

                                    <img
                                        src="{{ $requestItem->student->getProfileImageUrl() }}"
                                        class="h-20 w-20 rounded-3xl object-cover bg-white shadow-xl"
                                        alt="{{ $requestItem->student?->name ?? 'Student' }}"
                                    >

                                @else

                                    <img
                                        src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($requestItem->student?->email ?? 'student') }}"
                                        class="h-20 w-20 rounded-3xl bg-white shadow-xl"
                                        alt="{{ $requestItem->student?->name ?? 'Student' }}"
                                    >

                                @endif


                                <div class="flex-1 min-w-0">


                                    {{-- NAME + STATUS --}}

                                    <div class="flex flex-wrap items-center gap-3">

                                        <h3 class="text-2xl font-black text-slate-900 dark:text-white break-words">

                                            {{ $requestItem->student?->name ?? 'Unknown Student' }}

                                        </h3>


                                        {{-- PENDING BADGE --}}

                                        @if($requestItem->status === 'pending')

                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-500/15 text-amber-600">

                                                <i class="fas fa-clock mr-1"></i>

                                                PENDING

                                            </span>


                                        {{-- ACCEPTED BADGE --}}

                                        @elseif($requestItem->status === 'accepted')

                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-500/15 text-emerald-600">

                                                <i class="fas fa-circle-check mr-1"></i>

                                                ACCEPTED

                                            </span>


                                        {{-- COMPLETED BADGE --}}

                                        @elseif($requestItem->status === 'completed')

                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-blue-500/15 text-blue-600">

                                                <i class="fas fa-flag-checkered mr-1"></i>

                                                COMPLETED

                                            </span>


                                        {{-- REJECTED BADGE --}}

                                        @elseif($requestItem->status === 'rejected')

                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-red-500/15 text-red-600">

                                                <i class="fas fa-circle-xmark mr-1"></i>

                                                REJECTED

                                            </span>


                                        {{-- OTHER STATUS --}}

                                        @else

                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-slate-500/15 text-slate-500">

                                                {{ strtoupper($requestItem->status) }}

                                            </span>

                                        @endif

                                    </div>


                                    {{-- EMAIL --}}

                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 break-all">

                                        <i class="fas fa-envelope mr-2"></i>

                                        {{ $requestItem->student?->email ?? 'No email found' }}

                                    </p>


                                    {{-- OFFICIAL ID --}}

                                    @if($requestItem->student?->official_id)

                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                                            <i class="fas fa-id-card mr-2"></i>

                                            ID: {{ $requestItem->student->official_id }}

                                        </p>

                                    @endif


                                    {{-- DEPARTMENT + BATCH --}}

                                    @if(
                                        $requestItem->student?->department ||
                                        $requestItem->student?->batch
                                    )

                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                                            <i class="fas fa-building-columns mr-2"></i>

                                            {{ $requestItem->student?->department ?: 'Department not specified' }}

                                            @if($requestItem->student?->batch)

                                                <span class="mx-1">
                                                    ·
                                                </span>

                                                {{ $requestItem->student->batch }}

                                            @endif

                                        </p>

                                    @endif


                                    {{-- VIEW PROFILE --}}

                                    @if($requestItem->student)

                                        <a
                                            href="{{ route('profiles.student.show', $requestItem->student) }}"
                                            class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-sm font-black shadow-lg hover:scale-105 transition duration-300"
                                        >

                                            <i class="fas fa-user-graduate"></i>

                                            View Student Profile

                                        </a>

                                    @endif


                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- REQUEST DETAILS --}}
                            {{-- ================================================= --}}

                            <div class="mt-6 rounded-2xl bg-purple-500/5 border border-purple-500/15 p-5">

                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">


                                    <div class="flex-1">

                                        <p class="text-xs uppercase tracking-[0.20em] text-purple-500 font-black">
                                            Mentorship Request
                                        </p>

                                        <h4 class="mt-2 text-lg font-black text-slate-900 dark:text-white">

                                            {{ $requestItem->title ?: 'Career Mentorship Request' }}

                                        </h4>

                                    </div>


                                    <span class="text-xs text-slate-400 font-bold whitespace-nowrap">

                                        <i class="fas fa-calendar mr-1"></i>

                                        {{ $requestItem->created_at?->format('d M Y') }}

                                    </span>


                                </div>


                                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-7 whitespace-pre-line">

                                    {{ $requestItem->description ?: 'No request description provided.' }}

                                </p>


                                <div class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-xs text-slate-400 font-bold">


                                    <span>

                                        <i class="fas fa-paper-plane mr-1"></i>

                                        Requested:
                                        {{ $requestItem->created_at?->format('d M Y, h:i A') }}

                                    </span>


                                    @if($requestItem->updated_at)

                                        <span>

                                            <i class="fas fa-clock-rotate-left mr-1"></i>

                                            Updated:
                                            {{ $requestItem->updated_at->format('d M Y, h:i A') }}

                                        </span>

                                    @endif


                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- ACCEPTED INFORMATION --}}
                            {{-- ================================================= --}}

                            @if($requestItem->status === 'accepted')

                                <div class="mt-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-5">

                                    <div class="flex items-start gap-3">

                                        <div class="h-10 w-10 shrink-0 rounded-xl bg-emerald-500/15 text-emerald-600 flex items-center justify-center">

                                            <i class="fas fa-handshake"></i>

                                        </div>


                                        <div>

                                            <p class="text-xs font-black uppercase tracking-[0.15em] text-emerald-600">
                                                Active Mentorship
                                            </p>


                                            @if($requestItem->started_at)

                                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">

                                                    Mentorship started on

                                                    <strong>
                                                        {{ $requestItem->started_at->format('d M Y, h:i A') }}
                                                    </strong>

                                                </p>

                                            @else

                                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">

                                                    This mentorship is currently active.

                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- ================================================= --}}
                            {{-- COMPLETED INFORMATION --}}
                            {{-- ================================================= --}}

                            @if($requestItem->status === 'completed')

                                <div class="mt-4 rounded-2xl bg-blue-500/10 border border-blue-500/20 p-5">

                                    <div class="flex items-start gap-3">

                                        <div class="h-10 w-10 shrink-0 rounded-xl bg-blue-500/15 text-blue-600 flex items-center justify-center">

                                            <i class="fas fa-flag-checkered"></i>

                                        </div>


                                        <div>

                                            <p class="text-xs font-black uppercase tracking-[0.15em] text-blue-600">
                                                Mentorship Completed
                                            </p>


                                            @if($requestItem->started_at)

                                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">

                                                    Started:

                                                    <strong>
                                                        {{ $requestItem->started_at->format('d M Y, h:i A') }}
                                                    </strong>

                                                </p>

                                            @endif


                                            @if($requestItem->ended_at)

                                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">

                                                    Completed:

                                                    <strong>
                                                        {{ $requestItem->ended_at->format('d M Y, h:i A') }}
                                                    </strong>

                                                </p>

                                            @endif


                                            @if($requestItem->started_at && $requestItem->ended_at)

                                                <p class="mt-2 text-xs font-bold text-blue-500">

                                                    <i class="fas fa-clock mr-1"></i>

                                                    Mentorship journey successfully finished.

                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- ================================================= --}}
                            {{-- REJECTION INFORMATION --}}
                            {{-- ================================================= --}}

                            @if($requestItem->status === 'rejected')

                                <div class="mt-4 rounded-2xl bg-red-500/10 border border-red-500/20 p-5">

                                    <div class="flex items-start gap-3">

                                        <div class="h-10 w-10 shrink-0 rounded-xl bg-red-500/15 text-red-500 flex items-center justify-center">

                                            <i class="fas fa-message"></i>

                                        </div>


                                        <div class="flex-1">

                                            <p class="text-xs font-black uppercase tracking-[0.15em] text-red-500">
                                                Rejection Reason
                                            </p>


                                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 leading-6">

                                                {{ $requestItem->rejection_reason ?: 'Rejected by mentor.' }}

                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endif


                        </div>


                        {{-- ================================================= --}}
                        {{-- RIGHT SIDE / ACTION AREA --}}
                        {{-- ================================================= --}}

                        <div class="w-full xl:w-80 shrink-0">


                            {{-- ================================================= --}}
                            {{-- PENDING ACTIONS --}}
                            {{-- ================================================= --}}

                            @if($requestItem->status === 'pending')

                                <div class="space-y-4">


                                    {{-- CURRENT STATUS --}}

                                    <div class="rounded-2xl bg-amber-500/10 border border-amber-500/20 p-4 text-center">

                                        <i class="fas fa-clock text-amber-500 text-xl"></i>

                                        <p class="mt-2 font-black text-amber-600">
                                            Waiting for Decision
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            Accept or reject this mentorship request.
                                        </p>

                                    </div>


                                    {{-- ACCEPT FORM --}}

                                    <form
                                        method="POST"
                                        action="{{ route('mentors.accept', $requestItem) }}"
                                        onsubmit="return confirm('Are you sure you want to accept this mentorship request?');"
                                    >

                                        @csrf
                                        @method('PATCH')


                                        <button
                                            type="submit"
                                            class="uc-action-card w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 px-5 py-3.5 text-white font-black shadow-xl hover:shadow-2xl transition"
                                        >

                                            <i class="fas fa-check mr-2"></i>

                                            Accept Request

                                        </button>

                                    </form>


                                    {{-- REJECT FORM --}}

                                    <form
                                        method="POST"
                                        action="{{ route('mentors.reject', $requestItem) }}"
                                        class="space-y-3"
                                        onsubmit="return confirm('Are you sure you want to reject this mentorship request?');"
                                    >

                                        @csrf
                                        @method('PATCH')


                                        <div>

                                            <label
                                                for="rejection_reason_{{ $requestItem->id }}"
                                                class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                                            >
                                                Rejection Reason
                                                <span class="font-normal text-slate-400">
                                                    (Optional)
                                                </span>
                                            </label>


                                            <textarea
                                                id="rejection_reason_{{ $requestItem->id }}"
                                                name="rejection_reason"
                                                rows="4"
                                                maxlength="1000"
                                                placeholder="Example: I am currently unavailable to provide mentorship..."
                                                class="w-full rounded-2xl border border-red-500/20 bg-red-500/5 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                                            >{{ old('rejection_reason') }}</textarea>


                                            <p class="mt-1 text-xs text-slate-400">
                                                Maximum 1000 characters.
                                            </p>

                                        </div>


                                        <button
                                            type="submit"
                                            class="uc-action-card w-full rounded-2xl bg-red-500/15 border border-red-500/20 text-red-600 px-5 py-3.5 font-black hover:bg-red-500 hover:text-white transition"
                                        >

                                            <i class="fas fa-xmark mr-2"></i>

                                            Reject Request

                                        </button>

                                    </form>


                                </div>


                            {{-- ================================================= --}}
                            {{-- ACCEPTED ACTIONS --}}
                            {{-- ================================================= --}}

                            @elseif($requestItem->status === 'accepted')

                                <div class="space-y-4">


                                    {{-- ACCEPTED STATUS --}}

                                    <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-5 text-center">

                                        <div class="mx-auto h-12 w-12 rounded-2xl bg-emerald-500/15 text-emerald-600 flex items-center justify-center">

                                            <i class="fas fa-handshake text-xl"></i>

                                        </div>


                                        <p class="mt-3 font-black text-emerald-600">
                                            Active Mentorship
                                        </p>


                                        @if($requestItem->started_at)

                                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">

                                                Started

                                                <strong>
                                                    {{ $requestItem->started_at->format('d M Y') }}
                                                </strong>

                                            </p>

                                        @endif

                                    </div>


                                    {{-- COMPLETE MENTORSHIP FORM --}}

                                    <form
                                        method="POST"
                                        action="{{ route('mentors.complete', $requestItem) }}"
                                        onsubmit="return confirm('Are you sure you want to mark this mentorship as completed? This action will finish the active mentorship.');"
                                    >

                                        @csrf
                                        @method('PATCH')


                                        <button
                                            type="submit"
                                            class="uc-action-card w-full rounded-2xl bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600 px-5 py-3.5 text-white font-black shadow-xl hover:shadow-2xl transition"
                                        >

                                            <i class="fas fa-flag-checkered mr-2"></i>

                                            Complete Mentorship

                                        </button>

                                    </form>


                                    <p class="text-xs text-center text-slate-400 leading-5">

                                        Complete this mentorship only after the mentoring journey has finished.

                                    </p>


                                </div>


                            {{-- ================================================= --}}
                            {{-- COMPLETED STATUS --}}
                            {{-- ================================================= --}}

                            @elseif($requestItem->status === 'completed')

                                <div class="rounded-2xl bg-blue-500/10 border border-blue-500/20 p-6 text-center">


                                    <div class="mx-auto h-14 w-14 rounded-2xl bg-blue-500/15 text-blue-600 flex items-center justify-center">

                                        <i class="fas fa-flag-checkered text-xl"></i>

                                    </div>


                                    <p class="mt-4 font-black text-blue-600">
                                        Mentorship Completed
                                    </p>


                                    @if($requestItem->ended_at)

                                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">

                                            Completed on

                                            <strong>
                                                {{ $requestItem->ended_at->format('d M Y') }}
                                            </strong>

                                        </p>

                                    @endif


                                    <div class="mt-4 rounded-xl bg-blue-500/10 p-3">

                                        <p class="text-xs text-blue-600 font-bold">

                                            <i class="fas fa-circle-check mr-1"></i>

                                            This mentorship journey has been successfully closed.

                                        </p>

                                    </div>


                                </div>


                            {{-- ================================================= --}}
                            {{-- REJECTED STATUS --}}
                            {{-- ================================================= --}}

                            @elseif($requestItem->status === 'rejected')

                                <div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-6 text-center">


                                    <div class="mx-auto h-14 w-14 rounded-2xl bg-red-500/15 text-red-600 flex items-center justify-center">

                                        <i class="fas fa-circle-xmark text-xl"></i>

                                    </div>


                                    <p class="mt-4 font-black text-red-600">
                                        Request Rejected
                                    </p>


                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-5">

                                        The student may send another mentorship request later.

                                    </p>


                                </div>


                            {{-- ================================================= --}}
                            {{-- UNKNOWN STATUS --}}
                            {{-- ================================================= --}}

                            @else

                                <div class="rounded-2xl bg-slate-500/10 border border-slate-500/20 p-6 text-center">

                                    <div class="mx-auto h-14 w-14 rounded-2xl bg-slate-500/15 text-slate-500 flex items-center justify-center">

                                        <i class="fas fa-circle-info text-xl"></i>

                                    </div>


                                    <p class="mt-4 font-black text-slate-600 dark:text-slate-300">

                                        {{ ucfirst($requestItem->status) }}

                                    </p>

                                </div>

                            @endif


                        </div>


                    </div>

                </div>


            @empty


                {{-- ========================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ========================================================= --}}

                <div class="uc-card p-12 text-center">

                    <div class="relative z-10">


                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-violet-500 to-fuchsia-500 flex items-center justify-center shadow-xl">

                            <i class="fas fa-inbox text-3xl text-white"></i>

                        </div>


                        <h3 class="mt-6 text-2xl font-black text-slate-900 dark:text-white">

                            No Mentorship Requests Yet

                        </h3>


                        <p class="mt-3 text-slate-500 dark:text-slate-300 max-w-lg mx-auto leading-7">

                            When students send you mentorship requests,
                            they will appear here so you can review,
                            accept or reject them.

                        </p>


                        <a
    href="{{ route('alumni.dashboard') }}"
    class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-violet-600 to-fuchsia-500 px-6 py-3 text-white font-black shadow-xl hover:scale-105 transition"
>
    <i class="fas fa-arrow-left"></i>

    Back to Dashboard
</a>


                    </div>

                </div>


            @endforelse

        </div>


    </div>

</x-app-layout>