<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-black text-white">
            AI Import Preview
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-white/10">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                    Detected Records: {{ count($previewRows) }}
                </h3>
                <p class="text-slate-500 mt-2">
                    Review the detected data before saving to the database.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 dark:bg-slate-950">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Role</th>
                            <th class="px-4 py-3 text-left">Student ID</th>
                            <th class="px-4 py-3 text-left">Alumni ID</th>
                            <th class="px-4 py-3 text-left">Department</th>
                            <th class="px-4 py-3 text-left">Batch</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($previewRows as $row)
                            <tr class="border-t border-slate-200 dark:border-white/10">
                                <td class="px-4 py-3">{{ $row['name'] }}</td>
                                <td class="px-4 py-3">{{ $row['email'] }}</td>
                                <td class="px-4 py-3">{{ ucfirst($row['role']) }}</td>
                                <td class="px-4 py-3">{{ $row['student_id'] }}</td>
                                <td class="px-4 py-3">{{ $row['alumni_id'] }}</td>
                                <td class="px-4 py-3">{{ $row['department'] }}</td>
                                <td class="px-4 py-3">{{ $row['batch'] }}</td>
                                <td class="px-4 py-3">{{ ucfirst($row['status']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 flex flex-col md:flex-row gap-4">
                <form method="POST" action="{{ route('superadmin.verified-users.bulk-confirm') }}">
                    @csrf
                    <button type="submit"
                            class="px-8 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-cyan-600 text-white font-black shadow-xl">
                        Confirm Import
                    </button>
                </form>

                <a href="{{ route('superadmin.verified-users.index') }}"
                   class="px-8 py-3 rounded-2xl bg-slate-700 text-white font-black">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</x-app-layout>