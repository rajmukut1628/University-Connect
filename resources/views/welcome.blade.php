<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>University Connect</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('favicon.png') }}"
    >

    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        html {
            scroll-behavior: smooth;
        }

        @keyframes floatOrb {
            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-26px) scale(1.06);
            }
        }

        @keyframes scanLine {
            0% {
                transform: translateX(-120%);
            }

            100% {
                transform: translateX(120%);
            }
        }

        @keyframes pulseGlow {
            0%,
            100% {
                box-shadow: 0 0 35px rgba(99, 102, 241, .35);
            }

            50% {
                box-shadow: 0 0 80px rgba(236, 72, 153, .45);
            }
        }

        @keyframes gridMove {
            from {
                background-position: 0 0;
            }

            to {
                background-position: 80px 80px;
            }
        }

        .uc-glass {
            background:
                linear-gradient(
                    135deg,
                    rgba(255,255,255,.14),
                    rgba(255,255,255,.045)
                );

            backdrop-filter: blur(24px);

            border:
                1px solid rgba(255,255,255,.14);

            box-shadow:
                0 30px 90px rgba(0,0,0,.35);
        }

        .uc-card {
            position: relative;
            overflow: hidden;
            transition: .35s ease;
        }

        .uc-card:hover {
            transform:
                translateY(-10px)
                scale(1.015);
        }

        .uc-card::before {
            content: "";

            position: absolute;
            inset: 0;

            width: 45%;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255,255,255,.2),
                    transparent
                );

            transform:
                translateX(-120%);
        }

        .uc-card:hover::before {
            animation: scanLine 1.15s ease;
        }

        .orb {
            animation: floatOrb 7s ease-in-out infinite;
        }

        .glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        .ai-grid {
            background-image:

                linear-gradient(
                    rgba(99,102,241,.12) 1px,
                    transparent 1px
                ),

                linear-gradient(
                    90deg,
                    rgba(236,72,153,.12) 1px,
                    transparent 1px
                );

            background-size: 80px 80px;

            animation:
                gridMove 18s linear infinite;
        }
    </style>

</head>


<body class="font-sans bg-slate-950 text-white overflow-x-hidden">

    {{-- ========================================================= --}}
    {{-- BACKGROUND --}}
    {{-- ========================================================= --}}

    <div class="fixed inset-0 ai-grid opacity-40"></div>

    <div
        class="
            fixed
            inset-0
            bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,.38),transparent_32%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.32),transparent_32%)]
        "
    ></div>


    {{-- Floating Background Orbs --}}
    <div
        class="
            fixed
            inset-0
            overflow-hidden
            pointer-events-none
        "
    >

        <div
            class="
                orb
                absolute
                -top-32
                -left-32
                h-96
                w-96
                rounded-full
                bg-indigo-500/30
                blur-3xl
            "
        ></div>


        <div
            class="
                orb
                absolute
                top-40
                -right-32
                h-96
                w-96
                rounded-full
                bg-fuchsia-500/25
                blur-3xl
            "
            style="animation-delay: 2s"
        ></div>


        <div
            class="
                orb
                absolute
                -bottom-32
                left-1/3
                h-96
                w-96
                rounded-full
                bg-cyan-500/20
                blur-3xl
            "
            style="animation-delay: 4s"
        ></div>

    </div>



    <main class="relative z-10 min-h-screen">


        {{-- ===================================================== --}}
        {{-- NAVBAR --}}
        {{-- ===================================================== --}}

        <nav
            class="
                max-w-7xl
                mx-auto
                px-6
                py-6
                flex
                items-center
                justify-between
                gap-6
            "
        >

            {{-- Brand --}}
            <div class="flex items-center gap-4">

                <div
                    class="
                        h-14
                        w-14
                        rounded-2xl
                        bg-gradient-to-br
                        from-indigo-500
                        via-purple-500
                        to-pink-500
                        flex
                        items-center
                        justify-center
                        shadow-2xl
                        shadow-purple-500/40
                        glow
                    "
                >

                    <i class="fas fa-graduation-cap text-2xl"></i>

                </div>


                <div>

                    <h1 class="text-xl font-black">
                        University Connect
                    </h1>

                    <p
                        class="
                            text-xs
                            text-slate-400
                            font-bold
                        "
                    >
                        AI Powered Campus Network
                    </p>

                </div>

            </div>



            {{-- Login / Dashboard --}}
            <div class="flex items-center gap-3">

                @auth

                    <a
                        href="{{ route('dashboard') }}"
                        class="
                            px-5
                            py-3
                            rounded-2xl
                            bg-gradient-to-r
                            from-indigo-500
                            via-purple-500
                            to-fuchsia-500
                            font-bold
                            shadow-xl
                            shadow-fuchsia-500/25
                            hover:scale-105
                            transition
                        "
                    >

                        <i class="fas fa-table-columns mr-2"></i>

                        Dashboard

                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="
                            px-6
                            py-3
                            rounded-2xl
                            bg-gradient-to-r
                            from-indigo-500
                            via-purple-500
                            to-fuchsia-500
                            font-black
                            shadow-xl
                            shadow-fuchsia-500/25
                            hover:scale-105
                            transition
                        "
                    >

                        <i class="fas fa-right-to-bracket mr-2"></i>

                        Login

                    </a>

                @endauth

            </div>

        </nav>



        {{-- ===================================================== --}}
        {{-- HERO SECTION --}}
        {{-- ===================================================== --}}

        <section
            class="
                max-w-7xl
                mx-auto
                px-6
                pt-16
                pb-24
                grid
                grid-cols-1
                lg:grid-cols-2
                gap-14
                items-center
            "
        >


            {{-- Left Side --}}
            <div>


                {{-- Verified Badge --}}
                <div
                    class="
                        inline-flex
                        items-center
                        gap-3
                        px-4
                        py-2
                        rounded-full
                        bg-white/10
                        border
                        border-white/10
                        text-cyan-300
                        font-black
                        text-sm
                        mb-6
                    "
                >

                    <span
                        class="
                            h-2
                            w-2
                            rounded-full
                            bg-emerald-400
                            animate-pulse
                        "
                    ></span>

                    Official Database Verified Access

                </div>



                {{-- Heading --}}
                <h2
                    class="
                        text-5xl
                        md:text-7xl
                        font-black
                        leading-tight
                    "
                >

                    Smart Bridge Between

                    <span
                        class="
                            block
                            bg-gradient-to-r
                            from-cyan-300
                            via-indigo-300
                            to-fuchsia-300
                            bg-clip-text
                            text-transparent
                        "
                    >

                        Students & Alumni

                    </span>

                </h2>



                {{-- Description --}}
                <p
                    class="
                        mt-6
                        text-lg
                        text-slate-300
                        max-w-2xl
                        leading-relaxed
                    "
                >

                    University Connect is a smart digital ecosystem
                    that connects verified students, alumni and university
                    administration through mentorship, career opportunities,
                    events, networking and campus communication.

                </p>



                {{-- Login Information --}}
                @guest

                    <div
                        class="
                            mt-8
                            max-w-xl
                            rounded-3xl
                            border
                            border-cyan-400/15
                            bg-cyan-400/5
                            p-5
                        "
                    >

                        <div class="flex items-start gap-4">

                            <div
                                class="
                                    h-12
                                    w-12
                                    shrink-0
                                    rounded-2xl
                                    bg-cyan-500/15
                                    flex
                                    items-center
                                    justify-center
                                    text-cyan-300
                                "
                            >

                                <i class="fas fa-id-card"></i>

                            </div>


                            <div>

                                <p class="font-black text-white">

                                    Verified Account Access

                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-slate-400
                                        leading-6
                                    "
                                >

                                    Students and Alumni can access their
                                    university-provided accounts using their
                                    verified email address or Official ID.

                                </p>

                            </div>

                        </div>

                    </div>

                @endguest



                {{-- Statistics --}}
                <div
                    class="
                        mt-10
                        grid
                        grid-cols-3
                        gap-4
                        max-w-xl
                    "
                >


                    {{-- Students --}}
                    <div
                        class="
                            uc-glass
                            rounded-3xl
                            p-5
                            text-center
                        "
                    >

                        <p
                            class="
                                text-3xl
                                font-black
                                text-cyan-300
                            "
                        >
                            {{ $homeStats['students'] ?? 0 }}
                        </p>


                        <p
                            class="
                                text-xs
                                text-slate-400
                                font-bold
                                mt-1
                            "
                        >
                            Students
                        </p>

                    </div>



                    {{-- Alumni --}}
                    <div
                        class="
                            uc-glass
                            rounded-3xl
                            p-5
                            text-center
                        "
                    >

                        <p
                            class="
                                text-3xl
                                font-black
                                text-fuchsia-300
                            "
                        >
                            {{ $homeStats['alumni'] ?? 0 }}
                        </p>


                        <p
                            class="
                                text-xs
                                text-slate-400
                                font-bold
                                mt-1
                            "
                        >
                            Alumni
                        </p>

                    </div>



                    {{-- Opportunities --}}
                    <div
                        class="
                            uc-glass
                            rounded-3xl
                            p-5
                            text-center
                        "
                    >

                        <p
                            class="
                                text-3xl
                                font-black
                                text-emerald-300
                            "
                        >
                            {{ $homeStats['jobs'] ?? 0 }}
                        </p>


                        <p
                            class="
                                text-xs
                                text-slate-400
                                font-bold
                                mt-1
                            "
                        >
                            Jobs
                        </p>

                    </div>


                </div>

            </div>



            {{-- ================================================= --}}
            {{-- RIGHT SIDE PREVIEW --}}
            {{-- ================================================= --}}

            <div class="relative">


                <div
                    class="
                        uc-glass
                        rounded-[2rem]
                        p-6
                        uc-card
                    "
                >


                    <div
                        class="
                            rounded-[1.5rem]
                            bg-slate-950/80
                            border
                            border-white/10
                            overflow-hidden
                        "
                    >


                        {{-- Preview Header --}}
                        <div
                            class="
                                flex
                                items-center
                                gap-2
                                px-5
                                py-4
                                border-b
                                border-white/10
                            "
                        >

                            <span
                                class="
                                    h-3
                                    w-3
                                    rounded-full
                                    bg-red-400
                                "
                            ></span>

                            <span
                                class="
                                    h-3
                                    w-3
                                    rounded-full
                                    bg-yellow-400
                                "
                            ></span>

                            <span
                                class="
                                    h-3
                                    w-3
                                    rounded-full
                                    bg-green-400
                                "
                            ></span>


                            <p
                                class="
                                    ml-3
                                    text-xs
                                    text-slate-400
                                    font-bold
                                "
                            >

                                University Connect Overview

                            </p>

                        </div>



                        <div class="p-6 space-y-5">


                            {{-- Preview Stats --}}
                            <div
                                class="
                                    grid
                                    grid-cols-3
                                    gap-4
                                "
                            >


                                {{-- Students --}}
                                <div
                                    class="
                                        rounded-2xl
                                        bg-indigo-500/15
                                        p-4
                                    "
                                >

                                    <i
                                        class="
                                            fas
                                            fa-user-graduate
                                            text-cyan-300
                                            text-2xl
                                        "
                                    ></i>


                                    <p
                                        class="
                                            mt-4
                                            text-2xl
                                            font-black
                                        "
                                    >

                                        {{ $homeStats['students'] ?? 0 }}

                                    </p>


                                    <p
                                        class="
                                            text-xs
                                            text-slate-400
                                        "
                                    >

                                        Students

                                    </p>

                                </div>



                                {{-- Alumni --}}
                                <div
                                    class="
                                        rounded-2xl
                                        bg-fuchsia-500/15
                                        p-4
                                    "
                                >

                                    <i
                                        class="
                                            fas
                                            fa-award
                                            text-fuchsia-300
                                            text-2xl
                                        "
                                    ></i>


                                    <p
                                        class="
                                            mt-4
                                            text-2xl
                                            font-black
                                        "
                                    >

                                        {{ $homeStats['alumni'] ?? 0 }}

                                    </p>


                                    <p
                                        class="
                                            text-xs
                                            text-slate-400
                                        "
                                    >

                                        Alumni

                                    </p>

                                </div>



                                {{-- Jobs --}}
                                <div
                                    class="
                                        rounded-2xl
                                        bg-emerald-500/15
                                        p-4
                                    "
                                >

                                    <i
                                        class="
                                            fas
                                            fa-briefcase
                                            text-emerald-300
                                            text-2xl
                                        "
                                    ></i>


                                    <p
                                        class="
                                            mt-4
                                            text-2xl
                                            font-black
                                        "
                                    >

                                        {{ $homeStats['jobs'] ?? 0 }}

                                    </p>


                                    <p
                                        class="
                                            text-xs
                                            text-slate-400
                                        "
                                    >

                                        Jobs

                                    </p>

                                </div>


                            </div>



                            {{-- Career Analytics --}}
                            <div
                                class="
                                    rounded-3xl
                                    bg-white/5
                                    border
                                    border-white/10
                                    p-5
                                "
                            >


                                <div
                                    class="
                                        flex
                                        items-center
                                        justify-between
                                        mb-4
                                    "
                                >

                                    <p class="font-black">

                                        Career Growth Analytics

                                    </p>


                                    <span
                                        class="
                                            text-xs
                                            px-3
                                            py-1
                                            rounded-full
                                            bg-emerald-500/15
                                            text-emerald-300
                                            font-bold
                                        "
                                    >

                                        LIVE

                                    </span>

                                </div>



                                <div
                                    class="
                                        flex
                                        items-end
                                        gap-2
                                        h-40
                                    "
                                >

                                    @foreach ([40, 68, 50, 82, 64, 95, 78, 100, 85, 92] as $height)

                                        <div
                                            class="
                                                flex-1
                                                rounded-t-2xl
                                                bg-gradient-to-t
                                                from-indigo-600
                                                via-fuchsia-500
                                                to-cyan-300
                                                hover:scale-110
                                                transition
                                            "
                                            style="height: {{ $height }}%"
                                        ></div>

                                    @endforeach

                                </div>

                            </div>



                            {{-- Verified Access --}}
                            <div
                                class="
                                    rounded-3xl
                                    bg-gradient-to-r
                                    from-indigo-500/20
                                    to-fuchsia-500/20
                                    p-5
                                    border
                                    border-white/10
                                "
                            >

                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-4
                                    "
                                >


                                    <div
                                        class="
                                            h-14
                                            w-14
                                            rounded-2xl
                                            bg-gradient-to-br
                                            from-cyan-400
                                            to-fuchsia-500
                                            flex
                                            items-center
                                            justify-center
                                        "
                                    >

                                        <i
                                            class="
                                                fas
                                                fa-shield-halved
                                                text-white
                                                text-xl
                                            "
                                        ></i>

                                    </div>



                                    <div>

                                        <p class="font-black">

                                            Verified Access System

                                        </p>


                                        <p
                                            class="
                                                text-sm
                                                text-slate-400
                                                leading-5
                                            "
                                        >

                                            Student and Alumni accounts are
                                            provided through the university
                                            verified database.

                                        </p>

                                    </div>


                                </div>

                            </div>


                        </div>

                    </div>

                </div>

            </div>


        </section>



        {{-- ===================================================== --}}
        {{-- PLATFORM MODULES --}}
        {{-- ===================================================== --}}

        <section
            class="
                max-w-7xl
                mx-auto
                px-6
                py-20
            "
        >


            <div
                class="
                    text-center
                    max-w-3xl
                    mx-auto
                    mb-14
                "
            >


                <p
                    class="
                        text-sm
                        uppercase
                        tracking-[0.35em]
                        text-cyan-300
                        font-black
                    "
                >

                    Platform Modules

                </p>


                <h3
                    class="
                        mt-4
                        text-4xl
                        md:text-5xl
                        font-black
                    "
                >

                    Everything your university ecosystem needs

                </h3>


                <p
                    class="
                        mt-5
                        text-slate-400
                        leading-relaxed
                    "
                >

                    A connected digital environment designed for
                    administration, mentorship, career development,
                    networking and university communication.

                </p>

            </div>



            <div
                class="
                    grid
                    grid-cols-1
                    md:grid-cols-3
                    gap-6
                "
            >


                {{-- Administration --}}
                <div
                    class="
                        uc-glass
                        uc-card
                        rounded-3xl
                        p-7
                    "
                >


                    <div
                        class="
                            h-14
                            w-14
                            rounded-2xl
                            bg-gradient-to-br
                            from-indigo-500
                            to-cyan-500
                            flex
                            items-center
                            justify-center
                        "
                    >

                        <i
                            class="
                                fas
                                fa-user-shield
                                text-2xl
                            "
                        ></i>

                    </div>


                    <h4
                        class="
                            mt-6
                            text-2xl
                            font-black
                        "
                    >

                        Smart Administration

                    </h4>


                    <p
                        class="
                            mt-3
                            text-slate-400
                            leading-relaxed
                        "
                    >

                        Manage verified users, platform activities,
                        career opportunities, events, notices and
                        university services from one centralized system.

                    </p>

                </div>



                {{-- Mentorship --}}
                <div
                    class="
                        uc-glass
                        uc-card
                        rounded-3xl
                        p-7
                    "
                >


                    <div
                        class="
                            h-14
                            w-14
                            rounded-2xl
                            bg-gradient-to-br
                            from-fuchsia-500
                            to-pink-500
                            flex
                            items-center
                            justify-center
                        "
                    >

                        <i
                            class="
                                fas
                                fa-handshake-angle
                                text-2xl
                            "
                        ></i>

                    </div>


                    <h4
                        class="
                            mt-6
                            text-2xl
                            font-black
                        "
                    >

                        Mentorship Network

                    </h4>


                    <p
                        class="
                            mt-3
                            text-slate-400
                            leading-relaxed
                        "
                    >

                        Students can connect with verified alumni
                        mentors and receive guidance for academic,
                        professional and career development.

                    </p>

                </div>



                {{-- Career --}}
                <div
                    class="
                        uc-glass
                        uc-card
                        rounded-3xl
                        p-7
                    "
                >


                    <div
                        class="
                            h-14
                            w-14
                            rounded-2xl
                            bg-gradient-to-br
                            from-emerald-500
                            to-lime-500
                            flex
                            items-center
                            justify-center
                        "
                    >

                        <i
                            class="
                                fas
                                fa-briefcase
                                text-2xl
                            "
                        ></i>

                    </div>


                    <h4
                        class="
                            mt-6
                            text-2xl
                            font-black
                        "
                    >

                        Career Portal

                    </h4>


                    <p
                        class="
                            mt-3
                            text-slate-400
                            leading-relaxed
                        "
                    >

                        Alumni can share jobs and internships while
                        students discover relevant opportunities and
                        build stronger professional connections.

                    </p>

                </div>


            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- FOOTER --}}
        {{-- ===================================================== --}}

        <footer
            class="
                max-w-7xl
                mx-auto
                px-6
                pb-10
                pt-8
            "
        >


            <div
                class="
                    border-t
                    border-white/10
                    pt-8
                    flex
                    flex-col
                    md:flex-row
                    items-center
                    justify-between
                    gap-4
                "
            >


                <div
                    class="
                        flex
                        items-center
                        gap-3
                    "
                >


                    <div
                        class="
                            h-10
                            w-10
                            rounded-xl
                            bg-gradient-to-br
                            from-indigo-500
                            to-fuchsia-500
                            flex
                            items-center
                            justify-center
                        "
                    >

                        <i class="fas fa-graduation-cap"></i>

                    </div>



                    <div>

                        <p class="font-black">
                            University Connect
                        </p>


                        <p
                            class="
                                text-xs
                                text-slate-500
                            "
                        >

                            Verified Campus Network

                        </p>

                    </div>


                </div>



                <p
                    class="
                        text-sm
                        text-slate-500
                    "
                >

                    &copy; {{ date('Y') }} University Connect.
                    All rights reserved.

                </p>


            </div>

        </footer>


    </main>

</body>

</html>