<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,.35),transparent_35%)]"></div>

            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                    Admin Verification Center
                </p>
                <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                    Verified Users Upload Panel
                </h2>
                <p class="mt-3 text-slate-300 max-w-2xl">
                    Upload CSV data to allow only official students and alumni to register.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-600 font-black">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-xl">
                <p class="text-sm text-slate-500 font-bold">Total</p>
                <h3 class="mt-3 text-4xl font-black text-cyan-500">{{ $stats['total'] }}</h3>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-xl">
                <p class="text-sm text-slate-500 font-bold">Students</p>
                <h3 class="mt-3 text-4xl font-black text-blue-500">{{ $stats['students'] }}</h3>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-xl">
                <p class="text-sm text-slate-500 font-bold">Alumni</p>
                <h3 class="mt-3 text-4xl font-black text-purple-500">{{ $stats['alumni'] }}</h3>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-xl">
                <p class="text-sm text-slate-500 font-bold">Active</p>
                <h3 class="mt-3 text-4xl font-black text-emerald-500">{{ $stats['active'] }}</h3>
            </div>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
            <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                Upload Verified Users CSV
            </h3>

            <p class="mt-2 text-slate-500">
                Required columns: student_id, alumni_id, name, email, department, batch, role, status
            </p>

            <form method="POST"
                  action="{{ route('admin.verified-users.import') }}"
                  enctype="multipart/form-data"
                  class="mt-6 flex flex-col md:flex-row gap-4">
                @csrf

                <input type="file"
                       name="file"
                       accept=".csv,.txt"
                       required
                       class="flex-1 rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white">

                <button class="rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-8 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                    <i class="fas fa-upload mr-2"></i>
                    Import CSV
                </button>
            </form>

            @error('file')
                <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl overflow-x-auto">
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">
                Verified Users List
            </h3>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-white/10 text-slate-500">
                        <th class="py-4 px-3">Name</th>
                        <th class="py-4 px-3">Email</th>
                        <th class="py-4 px-3">Official ID</th>
                        <th class="py-4 px-3">Department</th>
                        <th class="py-4 px-3">Batch</th>
                        <th class="py-4 px-3">Role</th>
                        <th class="py-4 px-3">Status</th>
                        <th class="py-4 px-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($verifiedUsers as $verifiedUser)
                        <tr class="border-b border-slate-100 dark:border-white/5">
                            <td class="py-4 px-3 font-black text-slate-900 dark:text-white">
                                {{ $verifiedUser->name }}
                            </td>

                            <td class="py-4 px-3 text-slate-500">
                                {{ $verifiedUser->email }}
                            </td>

                            <td class="py-4 px-3 text-slate-500">
                                {{ $verifiedUser->student_id ?? $verifiedUser->alumni_id ?? 'N/A' }}
                            </td>

                            <td class="py-4 px-3 text-slate-500">
                                {{ $verifiedUser->department ?? 'N/A' }}
                            </td>

                            <td class="py-4 px-3 text-slate-500">
                                {{ $verifiedUser->batch ?? 'N/A' }}
                            </td>

                            <td class="py-4 px-3">
                                <span class="px-3 py-1 rounded-full bg-blue-500/15 text-blue-600 text-xs font-black">
                                    {{ ucfirst($verifiedUser->role) }}
                                </span>
                            </td>

                            <td class="py-4 px-3">
                                <span class="px-3 py-1 rounded-full {{ $verifiedUser->status === 'active' ? 'bg-emerald-500/15 text-emerald-600' : 'bg-red-500/15 text-red-600' }} text-xs font-black">
                                    {{ ucfirst($verifiedUser->status) }}
                                </span>
                            </td>

                            <td class="py-4 px-3 text-right">
                                <form method="POST"
                                      action="{{ route('admin.verified-users.destroy', $verifiedUser) }}"
                                      onsubmit="return confirm('Delete this verified user?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="rounded-xl bg-red-500/15 px-4 py-2 text-red-600 font-bold">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-500 font-bold">
                                No verified users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $verifiedUsers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>