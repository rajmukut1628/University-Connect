<x-app-layout>

    <x-slot name="header">

        <div class="rounded-3xl
                    bg-gradient-to-r
                    from-slate-950
                    via-indigo-950
                    to-purple-950
                    p-7
                    border border-white/10
                    shadow-2xl">

            <p class="text-xs uppercase
                      tracking-[0.3em]
                      text-cyan-300
                      font-black">

                Donation Management

            </p>

            <h2 class="mt-2 text-3xl
                       font-black text-white">

                Payment Verification

            </h2>

            <p class="mt-2 text-sm text-slate-300">

                Verify submitted bKash and Nagad
                donation payments.

            </p>

        </div>

    </x-slot>


    <div class="max-w-7xl mx-auto">


        @if(session('success'))

            <div class="mb-6 rounded-2xl
                        bg-emerald-500/15
                        border border-emerald-500/30
                        p-4
                        text-emerald-600
                        dark:text-emerald-300
                        font-bold">

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="mb-6 rounded-2xl
                        bg-red-500/15
                        border border-red-500/30
                        p-4
                        text-red-600
                        dark:text-red-300
                        font-bold">

                {{ session('error') }}

            </div>

        @endif


        @if($errors->any())

            <div class="mb-6 rounded-2xl
                        bg-red-500/15
                        border border-red-500/30
                        p-4
                        text-red-600
                        dark:text-red-300">

                @foreach($errors->all() as $error)

                    <p>{{ $error }}</p>

                @endforeach

            </div>

        @endif



        <div class="grid grid-cols-1 gap-6">


            @forelse($payments as $payment)


                <div class="rounded-[28px]
                            bg-white
                            dark:bg-slate-900
                            border
                            border-slate-200
                            dark:border-white/10
                            shadow-xl
                            p-6">


                    <div class="grid
                                grid-cols-1
                                lg:grid-cols-[1fr_auto]
                                gap-6">


                        <div>


                            <div class="flex flex-wrap
                                        items-center
                                        gap-3">

                                <span class="px-3 py-1
                                             rounded-full
                                             bg-amber-500/15
                                             text-amber-500
                                             text-xs
                                             font-black">

                                    PENDING

                                </span>


                                <span class="px-3 py-1
                                             rounded-full
                                             bg-cyan-500/15
                                             text-cyan-500
                                             text-xs
                                             font-black">

                                    {{ strtoupper($payment->payment_method) }}

                                </span>

                            </div>


                            <h3 class="mt-4
                                       text-xl
                                       font-black
                                       text-slate-900
                                       dark:text-white">

                                {{ $payment->donation->title ?? 'Donation Campaign' }}

                            </h3>


                            <div class="mt-5 grid
                                        grid-cols-1
                                        sm:grid-cols-2
                                        xl:grid-cols-4
                                        gap-4">


                                <div class="rounded-2xl
                                            bg-slate-100
                                            dark:bg-slate-950
                                            p-4">

                                    <p class="text-xs text-slate-500">
                                        Donor
                                    </p>

                                    <p class="mt-1 font-black
                                              text-slate-900
                                              dark:text-white">

                                        {{ $payment->user->name ?? 'Unknown' }}

                                    </p>

                                </div>


                                <div class="rounded-2xl
                                            bg-slate-100
                                            dark:bg-slate-950
                                            p-4">

                                    <p class="text-xs text-slate-500">
                                        Amount
                                    </p>

                                    <p class="mt-1 text-xl
                                              font-black
                                              text-emerald-500">

                                        ৳{{ number_format((float) $payment->amount, 2) }}

                                    </p>

                                </div>


                                <div class="rounded-2xl
                                            bg-slate-100
                                            dark:bg-slate-950
                                            p-4">

                                    <p class="text-xs text-slate-500">
                                        Sender Number
                                    </p>

                                    <p class="mt-1 font-black
                                              text-slate-900
                                              dark:text-white">

                                        {{ $payment->account_number }}

                                    </p>

                                </div>


                                <div class="rounded-2xl
                                            bg-slate-100
                                            dark:bg-slate-950
                                            p-4">

                                    <p class="text-xs text-slate-500">
                                        Transaction ID
                                    </p>

                                    <p class="mt-1 font-black
                                              text-slate-900
                                              dark:text-white">

                                        {{ $payment->transaction_id }}

                                    </p>

                                </div>

                            </div>


                            @if($payment->note)

                                <div class="mt-4
                                            rounded-2xl
                                            bg-slate-100
                                            dark:bg-slate-950
                                            p-4">

                                    <p class="text-xs text-slate-500">
                                        Note
                                    </p>

                                    <p class="mt-2
                                              text-slate-700
                                              dark:text-slate-300">

                                        {{ $payment->note }}

                                    </p>

                                </div>

                            @endif


                            @if($payment->screenshot)

                                <div class="mt-5">

                                    <p class="text-sm
                                              font-black
                                              text-slate-700
                                              dark:text-slate-300
                                              mb-3">

                                        Payment Screenshot

                                    </p>


                                    <a
                                        href="{{ route('secure.donation-payments.screenshot', $payment) }}"
                                        target="_blank"
                                        rel="noopener"
                                    >

                                        <img
                                            src="{{ route('secure.donation-payments.screenshot', $payment) }}"
                                            alt="Payment Screenshot"
                                            class="max-h-64
                                                   rounded-2xl
                                                   border
                                                   border-white/10
                                                   object-contain"
                                        >

                                    </a>

                                </div>

                            @endif


                        </div>



                        <div class="lg:w-72">


                            <div class="rounded-3xl
                                        bg-slate-100
                                        dark:bg-slate-950
                                        p-5">


                                <h4 class="font-black
                                           text-slate-900
                                           dark:text-white">

                                    Review Payment

                                </h4>


                                <form
                                    method="POST"
                                    action="{{ route('donation-payments.approve', $payment) }}"
                                    class="mt-5"
                                    onsubmit="return confirm('Approve this payment and add the amount to the campaign?');"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <button
                                        type="submit"
                                        class="w-full
                                               rounded-2xl
                                               bg-emerald-500
                                               hover:bg-emerald-600
                                               text-white
                                               font-black
                                               px-5 py-3
                                               transition"
                                    >

                                        ✓ Approve Payment

                                    </button>

                                </form>



                                <form
                                    method="POST"
                                    action="{{ route('donation-payments.reject', $payment) }}"
                                    class="mt-5"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <label class="block
                                                  text-sm
                                                  font-bold
                                                  text-slate-600
                                                  dark:text-slate-300
                                                  mb-2">

                                        Rejection Reason

                                    </label>


                                    <textarea
                                        name="rejection_reason"
                                        rows="3"
                                        required
                                        placeholder="Why is this payment being rejected?"
                                        class="w-full
                                               rounded-2xl
                                               border-slate-300
                                               dark:border-white/10
                                               dark:bg-slate-900
                                               dark:text-white"
                                    ></textarea>


                                    <button
                                        type="submit"
                                        class="mt-3
                                               w-full
                                               rounded-2xl
                                               bg-red-500
                                               hover:bg-red-600
                                               text-white
                                               font-black
                                               px-5 py-3
                                               transition"
                                    >

                                        ✕ Reject Payment

                                    </button>

                                </form>


                            </div>

                        </div>


                    </div>

                </div>


            @empty


                <div class="rounded-[28px]
                            bg-white
                            dark:bg-slate-900
                            border
                            border-slate-200
                            dark:border-white/10
                            p-12
                            text-center">

                    <div class="text-5xl">
                        ✓
                    </div>

                    <h3 class="mt-4
                               text-xl
                               font-black
                               text-slate-900
                               dark:text-white">

                        No Pending Payments

                    </h3>

                    <p class="mt-2 text-slate-500">

                        All submitted donation payments
                        have been reviewed.

                    </p>

                </div>


            @endforelse


        </div>


        @if($payments->hasPages())

            <div class="mt-8">

                {{ $payments->links() }}

            </div>

        @endif


    </div>

</x-app-layout>