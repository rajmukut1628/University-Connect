<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>University Connect</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes floatOrb {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-26px) scale(1.06); }
        }

        @keyframes scanLine {
            0% { transform: translateX(-120%); }
            100% { transform: translateX(120%); }
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 35px rgba(99,102,241,.35); }
            50% { box-shadow: 0 0 80px rgba(236,72,153,.45); }
        }

        @keyframes gridMove {
            from { background-position: 0 0; }
            to { background-position: 80px 80px; }
        }

        .uc-glass {
            background: linear-gradient(135deg, rgba(255,255,255,.14), rgba(255,255,255,.045));
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,.14);
            box-shadow: 0 30px 90px rgba(0,0,0,.35);
        }

        .uc-card {
            position: relative;
            overflow: hidden;
            transition: .35s ease;
        }

        .uc-card:hover {
            transform: translateY(-10px) scale(1.015);
        }

        .uc-card::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 45%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            transform: translateX(-120%);
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
                linear-gradient(rgba(99,102,241,.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(236,72,153,.12) 1px, transparent 1px);
            background-size: 80px 80px;
            animation: gridMove 18s linear infinite;
        }
    </style>
</head>

<body class="font-sans bg-slate-950 text-white overflow-x-hidden">

    <div class="fixed inset-0 ai-grid opacity-40"></div>
    <div class="fixed inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,.38),transparent_32%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.32),transparent_32%)]"></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="orb absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="orb absolute top-40 -right-32 h-96 w-96 rounded-full bg-fuchsia-500/25 blur-3xl" style="animation-delay: 2s"></div>
        <div class="orb absolute -bottom-32 left-1/3 h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl" style="animation-delay: 4s"></div>
    </div>

    <main class="relative z-10 min-h-screen">

        <nav class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-2xl shadow-purple-500/40 glow">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>

                <div>
                    <h1 class="text-xl font-black">University Connect</h1>
                    <p class="text-xs text-slate-400 font-bold">AI Powered Campus Network</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-3 rounded-2xl bg-white/10 border border-white/10 hover:bg-white/20 font-bold transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex px-5 py-3 rounded-2xl bg-white/10 border border-white/10 hover:bg-white/20 font-bold transition">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-500 to-fuchsia-500 hover:scale-105 font-bold shadow-xl shadow-fuchsia-500/25 transition">
                        Get Started
                    </a>
                @endauth
            </div>
        </nav>

        <section class="max-w-7xl mx-auto px-6 pt-16 pb-24 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

            <div>
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-cyan-300 font-black text-sm mb-6">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Official Database Verified Access
                </div>

                <h2 class="text-5xl md:text-7xl font-black leading-tight">
                    Smart Bridge Between
                    <span class="block bg-gradient-to-r from-cyan-300 via-indigo-300 to-fuchsia-300 bg-clip-text text-transparent">
                        Students & Alumni
                    </span>
                </h2>

                <p class="mt-6 text-lg text-slate-300 max-w-2xl leading-relaxed">
                    University Connect is an ultra-premium digital ecosystem for verified students, alumni and administration — built for mentorship, jobs, events, networking and smart campus communication.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-7 py-4 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-fuchsia-500 font-black shadow-2xl shadow-fuchsia-500/30 hover:scale-105 transition">
                            Open Dashboard
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-7 py-4 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-fuchsia-500 font-black shadow-2xl shadow-fuchsia-500/30 hover:scale-105 transition">
                            Create Verified Account
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>

                        <a href="{{ route('login') }}" class="px-7 py-4 rounded-2xl bg-white/10 border border-white/10 font-black hover:bg-white/20 transition">
                            Login Now
                        </a>
                    @endauth
                </div>

                <div class="mt-10 grid grid-cols-3 gap-4 max-w-xl">
                    <div class="uc-glass rounded-3xl p-5 text-center">
                        <p class="text-3xl font-black text-cyan-300">3</p>
                        <p class="text-xs text-slate-400 font-bold mt-1">Core Roles</p>
                    </div>

                    <div class="uc-glass rounded-3xl p-5 text-center">
                        <p class="text-3xl font-black text-fuchsia-300">AI</p>
                        <p class="text-xs text-slate-400 font-bold mt-1">Smart UI</p>
                    </div>

                    <div class="uc-glass rounded-3xl p-5 text-center">
                        <p class="text-3xl font-black text-emerald-300">100%</p>
                        <p class="text-xs text-slate-400 font-bold mt-1">Verified</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="uc-glass rounded-[2rem] p-6 uc-card">
                    <div class="rounded-[1.5rem] bg-slate-950/80 border border-white/10 overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-white/10">
                            <span class="h-3 w-3 rounded-full bg-red-400"></span>
                            <span class="h-3 w-3 rounded-full bg-yellow-400"></span>
                            <span class="h-3 w-3 rounded-full bg-green-400"></span>
                            <p class="ml-3 text-xs text-slate-400 font-bold">AI Campus Command Preview</p>
                        </div>

                        <div class="p-6 space-y-5">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="rounded-2xl bg-indigo-500/15 p-4">
                                    <i class="fas fa-user-graduate text-cyan-300 text-2xl"></i>
                                    <p class="mt-4 text-2xl font-black">{{ $homeStats['students'] ?? 0 }}</p>
                                    <p class="text-xs text-slate-400">Students</p>
                                </div>

                                <div class="rounded-2xl bg-fuchsia-500/15 p-4">
                                    <i class="fas fa-award text-fuchsia-300 text-2xl"></i>
                                    <p class="mt-4 text-2xl font-black">{{ $homeStats['alumni'] ?? 0 }}</p>
                                    <p class="text-xs text-slate-400">Alumni</p>
                                </div>

                                <div class="rounded-2xl bg-emerald-500/15 p-4">
                                    <i class="fas fa-briefcase text-emerald-300 text-2xl"></i>
                                    <p class="mt-4 text-2xl font-black">{{ $homeStats['jobs'] ?? 0 }}</p>
                                    <p class="text-xs text-slate-400">Jobs</p>
                                </div>
                            </div>

                            <div class="rounded-3xl bg-white/5 border border-white/10 p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <p class="font-black">Career Growth Analytics</p>
                                    <span class="text-xs px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 font-bold">LIVE</span>
                                </div>

                                <div class="flex items-end gap-2 h-40">
                                    @foreach ([40, 68, 50, 82, 64, 95, 78, 100, 85, 92] as $height)
                                        <div class="flex-1 rounded-t-2xl bg-gradient-to-t from-indigo-600 via-fuchsia-500 to-cyan-300 hover:scale-110 transition" style="height: {{ $height }}%"></div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-3xl bg-gradient-to-r from-indigo-500/20 to-fuchsia-500/20 p-5 border border-white/10">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-cyan-400 to-fuchsia-500 flex items-center justify-center">
                                        <i class="fas fa-shield-halved text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-black">Security Engine Active</p>
                                        <p class="text-sm text-slate-400">Only official students and alumni can register.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-20">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">Premium Modules</p>
                <h3 class="mt-4 text-4xl md:text-5xl font-black">Everything your university ecosystem needs</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="uc-glass uc-card rounded-3xl p-7">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                    <h4 class="mt-6 text-2xl font-black">Admin Intelligence</h4>
                    <p class="mt-3 text-slate-400">Manage users, verifications, jobs, notices, reports and platform health.</p>
                </div>

                <div class="uc-glass uc-card rounded-3xl p-7">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-handshake-angle text-2xl"></i>
                    </div>
                    <h4 class="mt-6 text-2xl font-black">Mentorship Network</h4>
                    <p class="mt-3 text-slate-400">Students can discover verified alumni mentors and request career guidance.</p>
                </div>

                <div class="uc-glass uc-card rounded-3xl p-7">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-lime-500 flex items-center justify-center">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                    <h4 class="mt-6 text-2xl font-black">Career Portal</h4>
                    <p class="mt-3 text-slate-400">Alumni can post jobs and internships, students can discover and apply.</p>
                </div>
            </div>
        </section>

    </main>
</body>
</html>