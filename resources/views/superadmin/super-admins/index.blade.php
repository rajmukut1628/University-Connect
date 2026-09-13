<x-app-layout>

    <x-slot name="header">
        <div class="rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-fuchsia-950 p-8 shadow-2xl border border-white/10">
            <p class="text-sm uppercase tracking-[0.35em] text-fuchsia-300 font-bold">
                Owner Super Admin
            </p>

            <h2 class="mt-3 text-4xl font-black text-white">
                Super Admin Management
            </h2>

            <p class="mt-3 text-slate-300 max-w-2xl">
                Manage Super Admin accounts, transfer ownership and remove normal Super Admins.
            </p>
        </div>
    </x-slot>


    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Top Actions --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                    Super Admin Accounts
                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Only the Owner Super Admin can access this section.
                </p>
            </div>


            <div class="flex flex-col sm:flex-row gap-3">

                <a
                    href="{{ route('superadmin.super-admins.create') }}"
                    class="px-6 py-3 rounded-2xl
                           bg-gradient-to-r from-fuchsia-500 to-purple-700
                           text-white font-black
                           shadow-xl hover:scale-105 transition
                           text-center"
                >
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Super Admin
                </a>


                <a
                    href="{{ route('superadmin.admins.create') }}"
                    class="px-6 py-3 rounded-2xl
                           bg-slate-950
                           text-white font-black
                           shadow-xl hover:scale-105 transition
                           text-center"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>

            </div>
        </div>


        {{-- Success --}}
        @if(session('success'))

            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 px-5 py-4 text-emerald-600 dark:text-emerald-400 font-bold">

                <i class="fas fa-circle-check mr-2"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- Error --}}
        @if(session('error'))

            <div class="rounded-2xl bg-red-500/10 border border-red-500/20 px-5 py-4 text-red-600 dark:text-red-400 font-bold">

                <i class="fas fa-triangle-exclamation mr-2"></i>

                {{ session('error') }}

            </div>

        @endif


        {{-- Super Admin Table --}}
        <div class="rounded-3xl bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-white/10
                    shadow-2xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-100 dark:bg-slate-950/70">

                        <tr class="text-left text-xs uppercase tracking-widest text-slate-500 dark:text-slate-400">

                            <th class="px-6 py-5">
                                #
                            </th>

                            <th class="px-6 py-5">
                                Super Admin
                            </th>

                            <th class="px-6 py-5">
                                Email
                            </th>

                            <th class="px-6 py-5">
                                Phone
                            </th>

                            <th class="px-6 py-5">
                                Account Status
                            </th>

                            <th class="px-6 py-5">
                                Ownership
                            </th>

                            <th class="px-6 py-5 text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">

                        @forelse($superAdmins as $superAdmin)

                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">

                                <td class="px-6 py-5 text-slate-500">
                                    {{ $loop->iteration }}
                                </td>


                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="h-12 w-12 rounded-2xl
                                                    bg-gradient-to-br from-purple-500 to-fuchsia-600
                                                    flex items-center justify-center
                                                    text-white font-black">

                                            {{ strtoupper(substr($superAdmin->name, 0, 1)) }}

                                        </div>


                                        <div>

                                            <p class="font-black text-slate-900 dark:text-white">
                                                {{ $superAdmin->name }}
                                            </p>


                                            @if(auth()->id() === $superAdmin->id)

                                                <span class="text-xs text-cyan-500 font-bold">
                                                    Current Account
                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-5 text-slate-600 dark:text-slate-300">
                                    {{ $superAdmin->email }}
                                </td>


                                <td class="px-6 py-5 text-slate-600 dark:text-slate-300">
                                    {{ $superAdmin->phone ?: 'N/A' }}
                                </td>


                                <td class="px-6 py-5">

                                    @if($superAdmin->is_blocked)

                                        <span class="px-3 py-1 rounded-full
                                                     bg-red-500/15 text-red-500
                                                     text-xs font-black">

                                            Blocked

                                        </span>

                                    @elseif($superAdmin->is_active)

                                        <span class="px-3 py-1 rounded-full
                                                     bg-emerald-500/15 text-emerald-500
                                                     text-xs font-black">

                                            Active

                                        </span>

                                    @else

                                        <span class="px-3 py-1 rounded-full
                                                     bg-amber-500/15 text-amber-500
                                                     text-xs font-black">

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-5">

                                    @if($superAdmin->is_owner)

                                        <span class="px-3 py-1 rounded-full
                                                     bg-fuchsia-500/15 text-fuchsia-500
                                                     text-xs font-black">

                                            <i class="fas fa-crown mr-1"></i>

                                            Owner

                                        </span>

                                    @else

                                        <span class="px-3 py-1 rounded-full
                                                     bg-indigo-500/15 text-indigo-500
                                                     text-xs font-black">

                                            Super Admin

                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-5">

                                    @if($superAdmin->is_owner)

                                        <div class="text-right">

                                            <span class="text-sm text-slate-500 font-bold">
                                                Current Owner
                                            </span>

                                        </div>

                                    @else

                                        <div class="flex flex-col xl:flex-row justify-end gap-2">


                                            {{-- Transfer Ownership --}}
                                            <form
                                                action="{{ route('superadmin.super-admins.transfer-ownership', $superAdmin) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to transfer ownership to {{ addslashes($superAdmin->name) }}? You will become a normal Super Admin after this transfer.');"
                                            >

                                                @csrf
                                                @method('PATCH')


                                                <button
                                                    type="submit"
                                                    class="w-full px-4 py-2 rounded-xl
                                                           bg-amber-500/15
                                                           text-amber-600
                                                           font-black text-xs
                                                           hover:bg-amber-500
                                                           hover:text-white
                                                           transition"
                                                >

                                                    <i class="fas fa-crown mr-1"></i>

                                                    Transfer Ownership

                                                </button>

                                            </form>


                                            {{-- Remove Super Admin --}}
                                            <form
                                                action="{{ route('superadmin.super-admins.destroy', $superAdmin) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to remove {{ addslashes($superAdmin->name) }} from Super Admin?');"
                                            >

                                                @csrf
                                                @method('DELETE')


                                                <button
                                                    type="submit"
                                                    class="w-full px-4 py-2 rounded-xl
                                                           bg-red-500/15
                                                           text-red-500
                                                           font-black text-xs
                                                           hover:bg-red-500
                                                           hover:text-white
                                                           transition"
                                                >

                                                    <i class="fas fa-trash mr-1"></i>

                                                    Remove

                                                </button>

                                            </form>

                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="px-6 py-12 text-center text-slate-500 font-bold">

                                    No Super Admin accounts found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Ownership Information --}}
        <div class="rounded-3xl border border-purple-500/20
                    bg-purple-500/10 p-6">

            <div class="flex items-start gap-4">

                <div class="h-12 w-12 rounded-2xl
                            bg-purple-500/15
                            text-purple-500
                            flex items-center justify-center shrink-0">

                    <i class="fas fa-crown text-xl"></i>

                </div>

                <div>

                    <h3 class="font-black text-purple-600 text-lg">
                        Ownership Information
                    </h3>

                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">

                        Only one Super Admin can be the Owner.
                        When ownership is transferred, the selected Super Admin
                        becomes the new Owner and the previous Owner remains
                        a normal Super Admin.

                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>