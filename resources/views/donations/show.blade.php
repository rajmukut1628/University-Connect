<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-slate-950 via-emerald-950 to-cyan-950 p-5 md:p-6 shadow-2xl border border-white/10">
            <div class="absolute -top-16 -right-16 w-44 h-44 bg-emerald-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 w-44 h-44 bg-cyan-400/20 rounded-full blur-3xl"></div>

            <div class="relative">
                <h2 class="text-2xl md:text-3xl font-black text-white">
                    {{ $donation->title }}
                </h2>
                <p class="mt-2 text-sm text-slate-300">
                    Compact donation campaign details and funding progress.
                </p>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes ucFadeUp {
            from {
                opacity: 0;
                transform: translateY(22px) scale(.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes ucFloat {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-6px);
            }
        }

        .uc-animate-card {
            animation: ucFadeUp .65s ease-out both;
        }

        .uc-float {
            animation: ucFloat 4s ease-in-out infinite;
        }
    </style>

    <div class="max-w-3xl mx-auto px-3 md:px-0">
        <div class="uc-animate-card relative overflow-hidden rounded-[30px] bg-white/90 dark:bg-slate-900/90 border border-slate-200 dark:border-white/10 p-4 md:p-5 shadow-2xl backdrop-blur-xl">

            <div class="absolute top-0 right-0 w-56 h-56 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-56 h-56 bg-cyan-400/10 rounded-full blur-3xl"></div>

            <div class="relative">

                @if(session('success'))
                    <div class="mb-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 px-4 py-3 text-emerald-600 dark:text-emerald-300 font-bold text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-2xl bg-red-500/15 border border-red-500/30 px-4 py-3 text-red-600 dark:text-red-300 font-bold text-sm">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($donation->image)
                    <div class="relative overflow-hidden rounded-[24px] mb-5 group border border-white/10 shadow-xl">
                        <img src="{{ asset('storage/' . $donation->image) }}"
                             class="h-36 md:h-44 w-full rounded-[24px] object-cover transition duration-700 group-hover:scale-105">

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between gap-3">
                            <div class="px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-xl border border-white/20 text-white text-xs font-black">
                                Donation Campaign
                            </div>

                            <div class="px-3 py-1.5 rounded-full bg-emerald-500/80 backdrop-blur-xl text-white text-xs font-black">
                                {{ $donation->progress }}%
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1.5 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-300 text-xs font-black">
                        {{ ucfirst($donation->status) }}
                    </span>

                    <span class="px-3 py-1.5 rounded-full bg-cyan-500/15 text-cyan-600 dark:text-cyan-300 text-xs font-black">
                        {{ $donation->category ?? 'General' }}
                    </span>
                </div>

                <p class="mt-5 text-sm md:text-base text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ $donation->description }}
                </p>

                <div class="mt-6 rounded-[24px] bg-slate-100/80 dark:bg-slate-950/70 border border-slate-200 dark:border-white/10 p-4">
                    <div class="flex justify-between gap-4 text-sm md:text-base font-black">
                        <span class="text-slate-900 dark:text-white">
                            Collected: ৳{{ number_format($donation->collected_amount, 2) }}
                        </span>

                        <span class="text-slate-900 dark:text-white">
                            Target: ৳{{ number_format($donation->target_amount, 2) }}
                        </span>
                    </div>

                    <div class="mt-3 h-3 w-full rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-500 transition-all duration-700"
                             style="width: {{ $donation->progress }}%"></div>
                    </div>

                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 font-bold">
                        {{ $donation->progress }}% funded
                    </p>
                </div>

                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-[22px] bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-white/10 p-4 hover:-translate-y-1 transition duration-300">
                        <p class="text-xs text-slate-500 font-bold">Posted By</p>
                        <p class="mt-1 font-black text-slate-900 dark:text-white">
                            {{ $donation->user->name ?? 'Unknown User' }}
                        </p>
                    </div>

                    <div class="rounded-[22px] bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-white/10 p-4 hover:-translate-y-1 transition duration-300">
                        <p class="text-xs text-slate-500 font-bold">Deadline</p>
                        <p class="mt-1 font-black text-slate-900 dark:text-white">
                            {{ $donation->deadline ? $donation->deadline->format('d M, Y') : 'Not specified' }}
                        </p>
                    </div>
                </div>
                                @if($donation->status === 'approved')
                    <div class="mt-6 rounded-[26px] bg-gradient-to-br from-emerald-500/10 via-cyan-500/10 to-blue-500/10 border border-emerald-500/20 p-5 md:p-6 shadow-xl">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-white text-xl shadow-lg uc-float">
                                💳
                            </div>

                            <div>
                                <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white">
                                    Donate Manually
                                </h3>
                                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400">
                                    Send money and submit transaction information.
                                </p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div class="rounded-[22px] bg-white/70 dark:bg-slate-950/70 border border-white/10 p-4">
                                <h4 class="font-black text-slate-900 dark:text-white mb-3">
                                    📱 Mobile Banking
                                </h4>
                                <div class="space-y-2 text-sm text-slate-700 dark:text-slate-300">
                                    <p><span class="font-bold">bKash:</span> 017XXXXXXXX</p>
                                    <p><span class="font-bold">Nagad:</span> 018XXXXXXXX</p>
                                    <p><span class="font-bold">Rocket:</span> 019XXXXXXXX</p>
                                    <p class="text-amber-500 font-bold">Send Money / Personal</p>
                                </div>
                            </div>

                            <div class="rounded-[22px] bg-white/70 dark:bg-slate-950/70 border border-white/10 p-4">
                                <h4 class="font-black text-slate-900 dark:text-white mb-3">
                                    🏦 Bank Transfer
                                </h4>
                                <div class="space-y-2 text-sm text-slate-700 dark:text-slate-300">
                                    <p><span class="font-bold">Bank:</span> Dutch-Bangla Bank Ltd.</p>
                                    <p><span class="font-bold">A/C Name:</span> University Connect Foundation</p>
                                    <p><span class="font-bold">A/C No:</span> 1234567890</p>
                                    <p><span class="font-bold">Branch:</span> Dhaka Main Branch</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('donations.manual-payment', $donation) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-black text-slate-700 dark:text-slate-300 mb-2">
                                        Payment Method
                                    </label>
                                    <select name="payment_method" required
                                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                        <option value="">Select Method</option>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-black text-slate-700 dark:text-slate-300 mb-2">
                                        Your Account Number
                                    </label>
                                    <input type="text"
                                           name="account_number"
                                           value="{{ old('account_number') }}"
                                           placeholder="01XXXXXXXXX"
                                           required
                                           class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-black text-slate-700 dark:text-slate-300 mb-2">
                                        Transaction ID
                                    </label>
                                    <input type="text"
                                           name="transaction_id"
                                           value="{{ old('transaction_id') }}"
                                           placeholder="TXN123456"
                                           required
                                           class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-black text-slate-700 dark:text-slate-300 mb-2">
                                        Amount (৳)
                                    </label>
                                    <input type="number"
                                           name="amount"
                                           value="{{ old('amount') }}"
                                           min="1"
                                           step="0.01"
                                           placeholder="500"
                                           required
                                           class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-black text-slate-700 dark:text-slate-300 mb-2">
                                    Payment Screenshot (Optional)
                                </label>
                                <input type="file"
                                       name="screenshot"
                                       accept="image/*"
                                       class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-black text-slate-700 dark:text-slate-300 mb-2">
                                    Note (Optional)
                                </label>
                                <textarea name="note"
                                          rows="3"
                                          placeholder="Any additional information..."
                                          class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">{{ old('note') }}</textarea>
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-500 text-white font-black shadow-xl hover:scale-105 transition duration-300">
                                🚀 Submit Payment
                            </button>
                        </form>
                    </div>
                @endif

                @if($donation->contributions()->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white">
                            Top Contributors
                        </h3>

                        <div class="mt-4 space-y-3">
                            @foreach($donation->contributions()->where('status', 'confirmed')->latest()->take(10)->get() as $contribution)
                                <div class="rounded-[22px] bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-white/10 p-4 flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-black text-slate-900 dark:text-white">
                                            {{ $contribution->is_anonymous ? 'Anonymous Donor' : ($contribution->donor_name ?? 'Supporter') }}
                                        </p>

                                        @if($contribution->message)
                                            <p class="text-sm text-slate-500 mt-1">
                                                “{{ $contribution->message }}”
                                            </p>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xl font-black text-emerald-500">
                                            ৳{{ number_format($contribution->amount, 2) }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ ucfirst($contribution->payment_method) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('donations.index') }}"
                       class="px-5 py-3 rounded-2xl bg-slate-950 text-white font-black hover:scale-105 transition">
                        Back
                    </a>

                    @if(auth()->check() && ($donation->user_id === auth()->id() || auth()->user()->isAdmin()))
                        <form method="POST"
                              action="{{ route('donations.destroy', $donation) }}"
                              onsubmit="return confirm('Are you sure you want to delete this donation post?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="px-5 py-3 rounded-2xl bg-red-500 text-white font-black shadow-xl hover:bg-red-600 hover:scale-105 transition">
                                Delete Post
                            </button>
                        </form>
                    @endif

                    @if(auth()->check() && auth()->user()->isAdmin() && $donation->status === 'pending')
                        <form method="POST" action="{{ route('donations.approve', $donation) }}">
                            @csrf
                            @method('PATCH')
                            <button class="px-5 py-3 rounded-2xl bg-emerald-500 text-white font-black hover:scale-105 transition">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('donations.reject', $donation) }}">
                            @csrf
                            @method('PATCH')
                            <button class="px-5 py-3 rounded-2xl bg-red-500 text-white font-black hover:scale-105 transition">
                                Reject
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>