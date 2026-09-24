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


        /* =========================================================
           UNIVERSITY CONNECT — ULTRA PREMIUM MOTION SYSTEM
           Visual-only enhancement. Existing Laravel flow is intact.
        ========================================================== */
        :root {
            --uc-cyan: 34 211 238;
            --uc-indigo: 99 102 241;
            --uc-violet: 139 92 246;
            --uc-pink: 236 72 153;
            --uc-emerald: 16 185 129;
            --uc-bg: 2 6 23;
        }

        body { position: relative; isolation: isolate; }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 9999;
            opacity: .035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.8'/%3E%3C/svg%3E");
        }

        #uc-cursor-glow {
            position: fixed; width: 520px; height: 520px; border-radius: 9999px;
            pointer-events: none; z-index: 1; opacity: .17;
            background: radial-gradient(circle, rgba(34,211,238,.75) 0%, rgba(99,102,241,.35) 30%, transparent 70%);
            filter: blur(12px); transform: translate(-50%,-50%); transition: opacity .25s ease;
            mix-blend-mode: screen;
        }

        .uc-nav-premium {
            position: sticky; top: 16px; z-index: 80; margin-top: 10px;
            border: 1px solid rgba(255,255,255,.10); border-radius: 26px;
            background: linear-gradient(135deg, rgba(15,23,42,.72), rgba(15,23,42,.40));
            backdrop-filter: blur(28px) saturate(145%);
            box-shadow: 0 22px 80px rgba(0,0,0,.25), inset 0 1px 0 rgba(255,255,255,.08);
        }

        .uc-brand-mark { position: relative; overflow: visible; }
        .uc-brand-mark::before, .uc-brand-mark::after {
            content:""; position:absolute; inset:-7px; border-radius:24px; border:1px solid rgba(103,232,249,.28);
            animation: ucRingPulse 3s ease-out infinite;
        }
        .uc-brand-mark::after { animation-delay: 1.5s; }
        @keyframes ucRingPulse { 0%{transform:scale(.8);opacity:0} 25%{opacity:.75} 100%{transform:scale(1.35);opacity:0} }

        .uc-hero-copy { animation: ucHeroIn .9s cubic-bezier(.2,.8,.2,1) both; }
        @keyframes ucHeroIn { from{opacity:0;transform:translateY(34px)} to{opacity:1;transform:none} }
        .uc-gradient-title { background-size: 220% auto !important; animation: ucTextFlow 5s linear infinite; }
        @keyframes ucTextFlow { to{background-position:220% center} }

        .uc-hero-stage { perspective: 1500px; min-height: 620px; display:flex; align-items:center; justify-content:center; }
        .uc-dashboard-3d { transform-style: preserve-3d; will-change: transform; transition: transform .15s ease-out; }
        .uc-dashboard-3d::before {
            content:""; position:absolute; inset:-2px; border-radius:2.15rem; padding:1px;
            background: linear-gradient(120deg, rgba(34,211,238,.9), transparent 28%, rgba(139,92,246,.7) 60%, rgba(236,72,153,.9));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude; opacity:.7; pointer-events:none;
        }

        .uc-orbit { position:absolute; border:1px solid rgba(125,211,252,.16); border-radius:50%; pointer-events:none; }
        .uc-orbit.one { width:640px;height:640px;animation:ucSpin 18s linear infinite; }
        .uc-orbit.two { width:520px;height:520px;border-color:rgba(244,114,182,.15);animation:ucSpinReverse 13s linear infinite; }
        .uc-orbit.three { width:760px;height:280px;transform:rotate(24deg);animation:ucOrbitTilt 15s ease-in-out infinite; }
        .uc-orbit-dot { position:absolute; width:10px;height:10px;border-radius:50%; background:#67e8f9; box-shadow:0 0 24px #22d3ee; left:50%; top:-5px; }
        @keyframes ucSpin { to{transform:rotate(360deg)} }
        @keyframes ucSpinReverse { to{transform:rotate(-360deg)} }
        @keyframes ucOrbitTilt { 0%,100%{transform:rotate(24deg) scale(.95)}50%{transform:rotate(-8deg) scale(1.05)} }

        .uc-float-chip { position:absolute; z-index:20; padding:12px 15px; border-radius:18px; border:1px solid rgba(255,255,255,.12); background:rgba(15,23,42,.68); backdrop-filter:blur(20px); box-shadow:0 16px 50px rgba(0,0,0,.32); animation:ucChipFloat 5s ease-in-out infinite; }
        .uc-float-chip.chip-a{top:7%;left:-7%;}.uc-float-chip.chip-b{right:-8%;top:22%;animation-delay:-1.7s}.uc-float-chip.chip-c{left:-5%;bottom:10%;animation-delay:-3.1s}
        @keyframes ucChipFloat{0%,100%{transform:translateY(0) rotate(-1deg)}50%{transform:translateY(-15px) rotate(1deg)}}

        .uc-stat-premium { position:relative; overflow:hidden; transition:transform .35s ease,border-color .35s ease,box-shadow .35s ease; }
        .uc-stat-premium:hover { transform:translateY(-8px); border-color:rgba(103,232,249,.26); box-shadow:0 24px 70px rgba(34,211,238,.08); }
        .uc-stat-premium::after { content:"";position:absolute;width:110px;height:110px;border-radius:50%;right:-55px;top:-55px;background:rgba(255,255,255,.08);filter:blur(4px);transition:.4s; }
        .uc-stat-premium:hover::after{transform:scale(1.45)}

        .uc-live-bar { transform-origin: bottom; animation: ucBarLive 3.2s ease-in-out infinite alternate; }
        .uc-live-bar:nth-child(2n){animation-delay:-.7s}.uc-live-bar:nth-child(3n){animation-delay:-1.3s}
        @keyframes ucBarLive { from{filter:saturate(.8) brightness(.85);transform:scaleY(.72)} to{filter:saturate(1.35) brightness(1.2);transform:scaleY(1)} }

        .uc-module-premium { min-height: 310px; transform-style:preserve-3d; border-color:rgba(255,255,255,.10); }
        .uc-module-premium:hover { box-shadow:0 34px 100px rgba(79,70,229,.16); border-color:rgba(129,140,248,.28); }
        .uc-module-premium .uc-module-icon { transition:transform .45s cubic-bezier(.2,.8,.2,1), box-shadow .45s ease; }
        .uc-module-premium:hover .uc-module-icon { transform:translateZ(30px) rotate(-7deg) scale(1.08); box-shadow:0 20px 50px rgba(99,102,241,.25); }

        .uc-reveal { opacity:0; transform:translateY(36px) scale(.98); transition:opacity .8s ease,transform .8s cubic-bezier(.2,.8,.2,1); }
        .uc-reveal.is-visible { opacity:1; transform:none; }

        .uc-scan { position:absolute; inset:0; pointer-events:none; overflow:hidden; border-radius:inherit; }
        .uc-scan::after { content:"";position:absolute;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(103,232,249,.75),transparent);box-shadow:0 0 18px rgba(34,211,238,.7);animation:ucScanY 5s linear infinite;opacity:.45; }
        @keyframes ucScanY { from{top:-5%}to{top:105%} }

        .uc-magnetic { transition:transform .2s ease, box-shadow .3s ease; }
        .uc-magnetic:hover { box-shadow:0 18px 60px rgba(168,85,247,.32); }

        .uc-status-dot { box-shadow:0 0 0 0 rgba(52,211,153,.6); animation:ucStatus 2s infinite; }
        @keyframes ucStatus { 70%{box-shadow:0 0 0 10px rgba(52,211,153,0)}100%{box-shadow:0 0 0 0 rgba(52,211,153,0)} }

        .uc-marquee-wrap { overflow:hidden; mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent); }
        .uc-marquee { display:flex;width:max-content;gap:14px;animation:ucMarquee 28s linear infinite; }
        @keyframes ucMarquee { to{transform:translateX(-50%)} }

        .uc-scroll-progress { position:fixed;top:0;left:0;height:2px;width:0;z-index:99999;background:linear-gradient(90deg,#22d3ee,#818cf8,#e879f9);box-shadow:0 0 18px rgba(34,211,238,.8); }

        @media (max-width: 1024px){ .uc-orbit,.uc-float-chip{display:none}.uc-hero-stage{min-height:auto}.uc-dashboard-3d{transform:none!important} }
        @media (prefers-reduced-motion: reduce){ *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;scroll-behavior:auto!important}.uc-reveal{opacity:1;transform:none}#uc-cursor-glow{display:none} }


        /* =========================================================
           DOUBLE ULTRA PRO — CINEMATIC EXPERIENCE LAYER
           Pure presentation/interaction. No Laravel process changes.
        ========================================================== */
        .uc-cinematic-shell {
            position: relative;
            isolation: isolate;
        }

        .uc-cinematic-shell::before {
            content: "";
            position: absolute;
            inset: -12%;
            pointer-events: none;
            background:
                radial-gradient(circle at 18% 30%, rgba(34,211,238,.10), transparent 25%),
                radial-gradient(circle at 80% 24%, rgba(168,85,247,.11), transparent 28%),
                radial-gradient(circle at 55% 80%, rgba(236,72,153,.08), transparent 30%);
            filter: blur(45px);
            animation: ucAtmosphere 14s ease-in-out infinite alternate;
        }

        @keyframes ucAtmosphere {
            0% { transform: translate3d(-2%, -1%, 0) scale(1); }
            50% { transform: translate3d(3%, 2%, 0) scale(1.08); }
            100% { transform: translate3d(-1%, 4%, 0) scale(1.03); }
        }

        .uc-holo-line {
            position: absolute;
            height: 1px;
            width: 180px;
            background: linear-gradient(90deg, transparent, rgba(103,232,249,.85), transparent);
            filter: drop-shadow(0 0 10px rgba(34,211,238,.7));
            opacity: .45;
            animation: ucHoloSweep 7s linear infinite;
        }

        @keyframes ucHoloSweep {
            0% { transform: translateX(-80px) scaleX(.7); opacity: 0; }
            15% { opacity: .5; }
            55% { opacity: .9; }
            100% { transform: translateX(260px) scaleX(1.2); opacity: 0; }
        }

        .uc-section-kicker {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem .85rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.045);
            color: rgb(165 243 252);
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .22em;
            text-transform: uppercase;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.06);
        }

        .uc-section-kicker::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgb(52 211 153);
            box-shadow: 0 0 18px rgba(52,211,153,.9);
            animation: ucDotPulse 1.8s ease-in-out infinite;
        }

        @keyframes ucDotPulse {
            0%,100% { transform: scale(.85); opacity: .55; }
            50% { transform: scale(1.25); opacity: 1; }
        }

        .uc-mega-title {
            font-size: clamp(2.2rem, 5vw, 5.2rem);
            line-height: .98;
            font-weight: 900;
            letter-spacing: -.055em;
        }

        .uc-prism-text {
            background: linear-gradient(100deg,#fff 0%,#a5f3fc 18%,#c4b5fd 42%,#f0abfc 64%,#67e8f9 84%,#fff 100%);
            background-size: 240% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: ucPrism 6s linear infinite;
        }

        @keyframes ucPrism { to { background-position: 240% center; } }

        .uc-command-center {
            position: relative;
            border-radius: 2.25rem;
            border: 1px solid rgba(255,255,255,.11);
            background:
                linear-gradient(145deg, rgba(15,23,42,.82), rgba(2,6,23,.66)),
                radial-gradient(circle at top right, rgba(99,102,241,.14), transparent 36%);
            backdrop-filter: blur(28px) saturate(150%);
            box-shadow:
                0 50px 130px rgba(0,0,0,.45),
                inset 0 1px 0 rgba(255,255,255,.08),
                0 0 0 1px rgba(99,102,241,.05);
            overflow: hidden;
        }

        .uc-command-center::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(115deg, transparent 20%, rgba(255,255,255,.055) 48%, transparent 72%);
            transform: translateX(-120%);
            animation: ucPanelShine 7s ease-in-out infinite;
        }

        @keyframes ucPanelShine {
            0%, 55% { transform: translateX(-120%); }
            78%, 100% { transform: translateX(120%); }
        }

        .uc-window-dots span {
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .uc-status-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .7rem;
            border-radius: 999px;
            font-size: .67rem;
            font-weight: 900;
            letter-spacing: .08em;
            border: 1px solid rgba(52,211,153,.18);
            color: rgb(110 231 183);
            background: rgba(16,185,129,.08);
        }

        .uc-status-pill::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgb(52 211 153);
            box-shadow: 0 0 12px rgba(52,211,153,.85);
        }

        .uc-network-visual {
            position: relative;
            min-height: 510px;
            border-radius: 2rem;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.08);
            background:
                radial-gradient(circle at center, rgba(99,102,241,.12), transparent 42%),
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px),
                rgba(2,6,23,.72);
            background-size: auto, 34px 34px, 34px 34px, auto;
        }

        #uc-network-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: .95;
        }

        .uc-network-core {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 118px;
            height: 118px;
            transform: translate(-50%,-50%);
            border-radius: 32px;
            display: grid;
            place-items: center;
            background: linear-gradient(145deg, rgba(34,211,238,.22), rgba(99,102,241,.28), rgba(236,72,153,.18));
            border: 1px solid rgba(165,243,252,.28);
            box-shadow: 0 0 80px rgba(99,102,241,.25), inset 0 0 35px rgba(255,255,255,.06);
            backdrop-filter: blur(18px);
            animation: ucCoreFloat 5s ease-in-out infinite;
        }

        .uc-network-core::before,
        .uc-network-core::after {
            content: "";
            position: absolute;
            inset: -22px;
            border-radius: 42px;
            border: 1px solid rgba(103,232,249,.16);
            animation: ucCoreRing 4s linear infinite;
        }

        .uc-network-core::after {
            inset: -48px;
            border-color: rgba(216,180,254,.10);
            animation-duration: 7s;
            animation-direction: reverse;
        }

        @keyframes ucCoreFloat {
            0%,100% { transform: translate(-50%,-50%) translateY(0) rotate(-1deg); }
            50% { transform: translate(-50%,-50%) translateY(-10px) rotate(1deg); }
        }

        @keyframes ucCoreRing {
            0% { transform: rotate(0deg) scale(.98); }
            50% { transform: rotate(180deg) scale(1.04); }
            100% { transform: rotate(360deg) scale(.98); }
        }

        .uc-node-label {
            position: absolute;
            min-width: 132px;
            padding: .8rem .9rem;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(15,23,42,.72);
            backdrop-filter: blur(18px);
            box-shadow: 0 18px 55px rgba(0,0,0,.28);
            font-size: .72rem;
            color: rgb(203 213 225);
            animation: ucNodeFloat 6s ease-in-out infinite;
        }

        .uc-node-label strong {
            display: block;
            color: white;
            font-size: .78rem;
            margin-bottom: .15rem;
        }

        .uc-node-label i { margin-right: .45rem; color: rgb(103 232 249); }

        .uc-node-1 { left: 6%; top: 13%; animation-delay: -.4s; }
        .uc-node-2 { right: 6%; top: 16%; animation-delay: -1.8s; }
        .uc-node-3 { left: 7%; bottom: 15%; animation-delay: -3.1s; }
        .uc-node-4 { right: 7%; bottom: 13%; animation-delay: -4.2s; }

        @keyframes ucNodeFloat {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .uc-mini-metric {
            border-radius: 1.35rem;
            border: 1px solid rgba(255,255,255,.08);
            background: linear-gradient(145deg, rgba(255,255,255,.055), rgba(255,255,255,.018));
            padding: 1.15rem;
            position: relative;
            overflow: hidden;
        }

        .uc-mini-metric::after {
            content: "";
            position: absolute;
            inset: auto -20% -65% 30%;
            height: 120px;
            border-radius: 50%;
            background: rgba(99,102,241,.18);
            filter: blur(30px);
        }

        .uc-spark {
            display: flex;
            align-items: end;
            gap: 4px;
            height: 34px;
        }

        .uc-spark span {
            flex: 1;
            min-width: 3px;
            border-radius: 999px;
            background: linear-gradient(to top, rgba(99,102,241,.65), rgba(103,232,249,.95));
            transform-origin: bottom;
            animation: ucSpark 2.4s ease-in-out infinite alternate;
        }

        @keyframes ucSpark {
            from { transform: scaleY(.65); opacity: .65; }
            to { transform: scaleY(1); opacity: 1; }
        }

        .uc-role-card {
            position: relative;
            overflow: hidden;
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,.10);
            background: linear-gradient(145deg, rgba(15,23,42,.78), rgba(15,23,42,.38));
            padding: 1.6rem;
            min-height: 280px;
            transform-style: preserve-3d;
            transition: transform .35s cubic-bezier(.2,.8,.2,1), border-color .35s ease, box-shadow .35s ease;
        }

        .uc-role-card:hover {
            transform: translateY(-10px);
            border-color: rgba(165,243,252,.25);
            box-shadow: 0 34px 90px rgba(0,0,0,.35);
        }

        .uc-role-card::before {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            right: -65px;
            top: -70px;
            border-radius: 50%;
            background: var(--role-glow, rgba(99,102,241,.24));
            filter: blur(32px);
            transition: transform .5s ease;
        }

        .uc-role-card:hover::before { transform: scale(1.35); }

        .uc-role-icon {
            width: 62px;
            height: 62px;
            border-radius: 20px;
            display: grid;
            place-items: center;
            font-size: 1.35rem;
            background: linear-gradient(145deg, rgba(255,255,255,.12), rgba(255,255,255,.035));
            border: 1px solid rgba(255,255,255,.10);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08);
        }

        .uc-flow-rail {
            position: relative;
        }

        .uc-flow-rail::before {
            content: "";
            position: absolute;
            left: 28px;
            top: 35px;
            bottom: 35px;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(103,232,249,.65), rgba(168,85,247,.55), rgba(52,211,153,.55), transparent);
        }

        .uc-flow-step {
            position: relative;
            display: grid;
            grid-template-columns: 58px 1fr;
            gap: 1.2rem;
            padding: 1rem 0;
        }

        .uc-flow-index {
            position: relative;
            z-index: 2;
            width: 58px;
            height: 58px;
            border-radius: 19px;
            display: grid;
            place-items: center;
            font-size: .78rem;
            font-weight: 900;
            background: rgba(15,23,42,.92);
            border: 1px solid rgba(103,232,249,.25);
            color: rgb(165 243 252);
            box-shadow: 0 0 35px rgba(34,211,238,.10);
        }

        .uc-flow-content {
            border-radius: 1.45rem;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.035);
            padding: 1.2rem 1.3rem;
            transition: .3s ease;
        }

        .uc-flow-step:hover .uc-flow-content {
            transform: translateX(7px);
            background: rgba(255,255,255,.055);
            border-color: rgba(165,243,252,.16);
        }

        .uc-orbit-console {
            position: relative;
            min-height: 440px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,.08);
            background: radial-gradient(circle, rgba(99,102,241,.13), transparent 56%), rgba(2,6,23,.5);
        }

        .uc-orbit-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(103,232,249,.16);
            animation: ucOrbitSpin 18s linear infinite;
        }

        .uc-orbit-ring.r1 { width: 170px; height: 170px; }
        .uc-orbit-ring.r2 { width: 280px; height: 280px; border-color: rgba(216,180,254,.14); animation-duration: 25s; animation-direction: reverse; }
        .uc-orbit-ring.r3 { width: 390px; height: 390px; border-style: dashed; border-color: rgba(52,211,153,.12); animation-duration: 34s; }

        @keyframes ucOrbitSpin { to { transform: rotate(360deg); } }

        .uc-orbit-ring::after {
            content: "";
            position: absolute;
            width: 10px;
            height: 10px;
            top: 50%;
            left: -5px;
            border-radius: 50%;
            background: rgb(103 232 249);
            box-shadow: 0 0 18px rgba(103,232,249,.9);
        }

        .uc-orbit-avatar {
            width: 112px;
            height: 112px;
            border-radius: 34px;
            display: grid;
            place-items: center;
            font-size: 2.1rem;
            background: linear-gradient(145deg, rgba(34,211,238,.20), rgba(99,102,241,.26), rgba(236,72,153,.17));
            border: 1px solid rgba(255,255,255,.16);
            box-shadow: 0 0 75px rgba(99,102,241,.25), inset 0 1px 0 rgba(255,255,255,.10);
            animation: ucAvatarHover 4s ease-in-out infinite;
        }

        @keyframes ucAvatarHover {
            0%,100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-12px) rotate(2deg); }
        }

        .uc-float-chip {
            position: absolute;
            padding: .7rem .85rem;
            border-radius: 1rem;
            background: rgba(15,23,42,.80);
            border: 1px solid rgba(255,255,255,.09);
            backdrop-filter: blur(14px);
            font-size: .68rem;
            font-weight: 800;
            color: rgb(203 213 225);
            box-shadow: 0 15px 45px rgba(0,0,0,.25);
            animation: ucChipFloat 5s ease-in-out infinite;
        }

        .uc-chip-a { left: 9%; top: 17%; }
        .uc-chip-b { right: 8%; top: 24%; animation-delay: -1.4s; }
        .uc-chip-c { left: 13%; bottom: 17%; animation-delay: -2.7s; }
        .uc-chip-d { right: 11%; bottom: 14%; animation-delay: -3.8s; }

        @keyframes ucChipFloat {
            0%,100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-9px) translateX(4px); }
        }

        .uc-cta-ultra {
            position: relative;
            overflow: hidden;
            border-radius: 2.5rem;
            border: 1px solid rgba(255,255,255,.13);
            background:
                radial-gradient(circle at 15% 20%, rgba(34,211,238,.16), transparent 27%),
                radial-gradient(circle at 85% 80%, rgba(236,72,153,.15), transparent 28%),
                linear-gradient(145deg, rgba(30,41,59,.72), rgba(2,6,23,.76));
            box-shadow: 0 45px 120px rgba(0,0,0,.38), inset 0 1px 0 rgba(255,255,255,.08);
        }

        .uc-cta-ultra::before {
            content: "";
            position: absolute;
            inset: -2px;
            padding: 1px;
            border-radius: inherit;
            background: linear-gradient(100deg, transparent, rgba(103,232,249,.55), rgba(216,180,254,.45), transparent);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            background-size: 220% 100%;
            animation: ucBorderRun 6s linear infinite;
            pointer-events: none;
        }

        @keyframes ucBorderRun { to { background-position: 220% 0; } }

        .uc-primary-cta {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .7rem;
            padding: .95rem 1.3rem;
            border-radius: 1.15rem;
            font-weight: 900;
            overflow: hidden;
            background: linear-gradient(100deg, rgb(6 182 212), rgb(99 102 241), rgb(217 70 239));
            box-shadow: 0 16px 50px rgba(99,102,241,.28);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .uc-primary-cta:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 22px 65px rgba(99,102,241,.40);
        }

        .uc-primary-cta::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -40%;
            width: 35%;
            height: 200%;
            transform: rotate(22deg);
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent);
            animation: ucButtonSweep 3.8s ease-in-out infinite;
        }

        @keyframes ucButtonSweep {
            0%,55% { left: -45%; }
            100% { left: 125%; }
        }

        .uc-magnetic { will-change: transform; transition: transform .18s ease-out; }

        .uc-live-beam {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            height: 1px;
            z-index: 90;
            pointer-events: none;
            background: linear-gradient(90deg, transparent, rgba(103,232,249,.75), rgba(216,180,254,.75), transparent);
            box-shadow: 0 0 18px rgba(103,232,249,.45);
            animation: ucBeam 5s linear infinite;
            opacity: .65;
        }

        @keyframes ucBeam {
            0% { transform: translateY(0); opacity: 0; }
            10% { opacity: .7; }
            90% { opacity: .4; }
            100% { transform: translateY(100vh); opacity: 0; }
        }

        .uc-depth-particle {
            position: fixed;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: white;
            pointer-events: none;
            opacity: .28;
            z-index: 0;
            animation: ucDepthRise var(--dur, 16s) linear infinite;
            animation-delay: var(--delay, 0s);
        }

        @keyframes ucDepthRise {
            from { transform: translate3d(0, 105vh, 0) scale(.7); opacity: 0; }
            15% { opacity: .28; }
            85% { opacity: .18; }
            to { transform: translate3d(var(--drift, 30px), -10vh, 0) scale(1.35); opacity: 0; }
        }

        @media (max-width: 767px) {
            .uc-network-visual { min-height: 430px; }
            .uc-node-label { min-width: 112px; padding: .65rem; font-size: .62rem; }
            .uc-node-1 { left: 3%; top: 8%; }
            .uc-node-2 { right: 3%; top: 12%; }
            .uc-node-3 { left: 3%; bottom: 9%; }
            .uc-node-4 { right: 3%; bottom: 7%; }
            .uc-network-core { width: 90px; height: 90px; border-radius: 27px; }
            .uc-orbit-console { min-height: 380px; }
            .uc-orbit-ring.r3 { width: 320px; height: 320px; }
            .uc-float-chip { font-size: .6rem; padding: .55rem .65rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .uc-cinematic-shell::before,
            .uc-holo-line,
            .uc-network-core,
            .uc-network-core::before,
            .uc-network-core::after,
            .uc-node-label,
            .uc-spark span,
            .uc-orbit-ring,
            .uc-orbit-avatar,
            .uc-float-chip,
            .uc-primary-cta::after,
            .uc-live-beam,
            .uc-depth-particle,
            .uc-prism-text {
                animation: none !important;
            }
        }


        /* =========================================================
           4X ULTRA PREMIUM LIVE — EXPERIENCE ENGINE V4
           Visual-only layer. Existing Laravel flow remains intact.
        ========================================================== */
        :root {
            --v4-cyan: 103,232,249;
            --v4-blue: 59,130,246;
            --v4-violet: 139,92,246;
            --v4-rose: 244,114,182;
            --v4-emerald: 52,211,153;
            --v4-ink: 2,6,23;
        }

        .v4-stage {
            position: relative;
            isolation: isolate;
        }

        .v4-aurora {
            position: absolute;
            inset: -20%;
            pointer-events: none;
            filter: blur(70px) saturate(140%);
            opacity: .55;
            background:
                conic-gradient(from 90deg at 50% 50%,
                    rgba(var(--v4-cyan),.14),
                    rgba(var(--v4-violet),.13),
                    rgba(var(--v4-rose),.10),
                    rgba(var(--v4-blue),.13),
                    rgba(var(--v4-cyan),.14));
            animation: v4Aurora 18s linear infinite;
            border-radius: 45%;
        }

        @keyframes v4Aurora {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.12); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .v4-kicker {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            padding: .62rem .9rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.11);
            background: linear-gradient(135deg,rgba(255,255,255,.07),rgba(255,255,255,.025));
            backdrop-filter: blur(18px);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08),0 12px 40px rgba(0,0,0,.16);
            font-size: .68rem;
            font-weight: 900;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: rgb(207 250 254);
        }

        .v4-kicker .v4-signal {
            position: relative;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgb(var(--v4-emerald));
            box-shadow: 0 0 18px rgba(var(--v4-emerald),.9);
        }

        .v4-kicker .v4-signal::after {
            content: "";
            position: absolute;
            inset: -5px;
            border: 1px solid rgba(var(--v4-emerald),.45);
            border-radius: inherit;
            animation: v4Signal 1.8s ease-out infinite;
        }

        @keyframes v4Signal {
            from { transform: scale(.55); opacity: .9; }
            to { transform: scale(1.8); opacity: 0; }
        }

        .v4-title {
            font-size: clamp(2.7rem,7vw,6.9rem);
            line-height: .88;
            letter-spacing: -.07em;
            font-weight: 900;
        }

        .v4-liquid-text {
            background:
                linear-gradient(100deg,#fff 0%,#cffafe 13%,#67e8f9 28%,#a5b4fc 43%,#d8b4fe 58%,#f9a8d4 73%,#93c5fd 88%,#fff 100%);
            background-size: 300% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: v4LiquidText 7s linear infinite;
            filter: drop-shadow(0 0 24px rgba(103,232,249,.08));
        }

        @keyframes v4LiquidText { to { background-position: 300% center; } }

        .v4-glass {
            border: 1px solid rgba(255,255,255,.10);
            background:
                linear-gradient(145deg,rgba(255,255,255,.065),rgba(255,255,255,.018)),
                rgba(2,6,23,.42);
            backdrop-filter: blur(28px) saturate(150%);
            box-shadow: 0 30px 90px rgba(0,0,0,.32), inset 0 1px 0 rgba(255,255,255,.07);
        }

        .v4-holo-board {
            position: relative;
            min-height: 650px;
            border-radius: 2.8rem;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.12);
            background:
                radial-gradient(circle at 50% 45%,rgba(var(--v4-violet),.17),transparent 26%),
                radial-gradient(circle at 20% 20%,rgba(var(--v4-cyan),.11),transparent 25%),
                radial-gradient(circle at 80% 75%,rgba(var(--v4-rose),.09),transparent 28%),
                linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px),
                rgba(2,6,23,.74);
            background-size:auto,auto,auto,40px 40px,40px 40px,auto;
            box-shadow:0 60px 150px rgba(0,0,0,.48),inset 0 1px 0 rgba(255,255,255,.08);
            transform-style:preserve-3d;
            perspective:1200px;
        }

        .v4-holo-board::before {
            content:"";
            position:absolute;
            inset:0;
            pointer-events:none;
            background:linear-gradient(115deg,transparent 15%,rgba(255,255,255,.05) 46%,transparent 70%);
            transform:translateX(-130%);
            animation:v4GlassSweep 8s ease-in-out infinite;
        }

        @keyframes v4GlassSweep {
            0%,58% { transform:translateX(-130%); }
            82%,100% { transform:translateX(130%); }
        }

        #v4-constellation {
            position:absolute;
            inset:0;
            width:100%;
            height:100%;
        }

        .v4-core {
            position:absolute;
            left:50%;
            top:47%;
            width:138px;
            height:138px;
            transform:translate(-50%,-50%);
            border-radius:40px;
            display:grid;
            place-items:center;
            z-index:5;
            background:
                linear-gradient(145deg,rgba(var(--v4-cyan),.24),rgba(var(--v4-violet),.30),rgba(var(--v4-rose),.18));
            border:1px solid rgba(207,250,254,.25);
            box-shadow:0 0 110px rgba(var(--v4-violet),.27),inset 0 1px 0 rgba(255,255,255,.16);
            backdrop-filter:blur(22px);
            animation:v4CoreHover 5s ease-in-out infinite;
        }

        @keyframes v4CoreHover {
            0%,100% { transform:translate(-50%,-50%) translateY(0) rotate(-2deg); }
            50% { transform:translate(-50%,-50%) translateY(-14px) rotate(2deg); }
        }

        .v4-core-ring {
            position:absolute;
            left:50%;
            top:47%;
            border-radius:50%;
            border:1px solid rgba(var(--v4-cyan),.15);
            transform:translate(-50%,-50%);
            pointer-events:none;
        }
        .v4-core-ring.a { width:210px;height:210px;animation:v4Spin 14s linear infinite; }
        .v4-core-ring.b { width:330px;height:330px;border-color:rgba(var(--v4-violet),.13);animation:v4Spin 23s linear infinite reverse; }
        .v4-core-ring.c { width:465px;height:465px;border-style:dashed;border-color:rgba(var(--v4-emerald),.11);animation:v4Spin 34s linear infinite; }

        .v4-core-ring::after {
            content:"";
            position:absolute;
            width:10px;height:10px;
            top:50%;left:-5px;
            border-radius:50%;
            background:rgb(var(--v4-cyan));
            box-shadow:0 0 20px rgba(var(--v4-cyan),.9);
        }

        @keyframes v4Spin { to { transform:translate(-50%,-50%) rotate(360deg); } }

        .v4-data-pod {
            position:absolute;
            z-index:6;
            min-width:150px;
            padding:.9rem 1rem;
            border-radius:1.15rem;
            border:1px solid rgba(255,255,255,.10);
            background:rgba(15,23,42,.72);
            backdrop-filter:blur(20px);
            box-shadow:0 20px 55px rgba(0,0,0,.30);
            animation:v4PodFloat 6s ease-in-out infinite;
        }

        .v4-data-pod strong { display:block;font-size:.8rem;color:#fff; }
        .v4-data-pod small { display:block;margin-top:.2rem;font-size:.65rem;color:rgb(148 163 184); }
        .v4-data-pod i { color:rgb(var(--v4-cyan));margin-right:.45rem; }
        .v4-pod-1 { left:6%;top:13%; }
        .v4-pod-2 { right:6%;top:17%;animation-delay:-1.3s; }
        .v4-pod-3 { left:7%;bottom:14%;animation-delay:-2.6s; }
        .v4-pod-4 { right:7%;bottom:12%;animation-delay:-3.9s; }

        @keyframes v4PodFloat {
            0%,100% { transform:translate3d(0,0,0) rotate(-.5deg); }
            50% { transform:translate3d(0,-12px,0) rotate(.5deg); }
        }

        .v4-live-strip {
            position:absolute;
            left:7%;
            right:7%;
            bottom:5%;
            z-index:7;
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:.65rem;
        }

        .v4-live-stat {
            border-radius:1.1rem;
            padding:.8rem .9rem;
            border:1px solid rgba(255,255,255,.08);
            background:rgba(15,23,42,.58);
            backdrop-filter:blur(16px);
        }

        .v4-live-stat p:first-child {
            font-size:.58rem;
            text-transform:uppercase;
            letter-spacing:.17em;
            color:rgb(100 116 139);
            font-weight:900;
        }

        .v4-live-stat p:last-child {
            font-size:1.15rem;
            margin-top:.25rem;
            font-weight:900;
            color:white;
        }

        .v4-wave {
            position:absolute;
            left:-10%;
            right:-10%;
            height:140px;
            border-radius:50%;
            border-top:1px solid rgba(var(--v4-cyan),.16);
            filter:drop-shadow(0 -4px 14px rgba(var(--v4-cyan),.08));
            pointer-events:none;
            animation:v4Wave 8s ease-in-out infinite;
        }
        .v4-wave.w1 { top:23%; }
        .v4-wave.w2 { top:28%;border-color:rgba(var(--v4-violet),.12);animation-delay:-2.7s; }
        .v4-wave.w3 { top:34%;border-color:rgba(var(--v4-rose),.10);animation-delay:-5.1s; }

        @keyframes v4Wave {
            0%,100% { transform:translateY(0) scaleX(1);opacity:.4; }
            50% { transform:translateY(15px) scaleX(1.08);opacity:.8; }
        }

        .v4-feature-bento {
            display:grid;
            grid-template-columns:repeat(12,minmax(0,1fr));
            gap:1rem;
        }

        .v4-bento {
            position:relative;
            overflow:hidden;
            border-radius:2rem;
            padding:1.6rem;
            border:1px solid rgba(255,255,255,.09);
            background:
                linear-gradient(145deg,rgba(255,255,255,.06),rgba(255,255,255,.018)),
                rgba(2,6,23,.50);
            backdrop-filter:blur(24px);
            box-shadow:0 28px 75px rgba(0,0,0,.25),inset 0 1px 0 rgba(255,255,255,.06);
            min-height:260px;
            transition:transform .4s cubic-bezier(.2,.8,.2,1),border-color .35s ease,box-shadow .35s ease;
        }

        .v4-bento:hover {
            transform:translateY(-8px);
            border-color:rgba(var(--v4-cyan),.20);
            box-shadow:0 38px 100px rgba(0,0,0,.36),inset 0 1px 0 rgba(255,255,255,.09);
        }

        .v4-bento::after {
            content:"";
            position:absolute;
            width:220px;height:220px;
            right:-100px;top:-100px;
            border-radius:50%;
            background:var(--v4-card-glow,rgba(var(--v4-violet),.16));
            filter:blur(45px);
            transition:transform .5s ease;
        }
        .v4-bento:hover::after { transform:scale(1.35); }

        .v4-span-7 { grid-column:span 7; }
        .v4-span-5 { grid-column:span 5; }
        .v4-span-4 { grid-column:span 4; }

        .v4-icon-box {
            width:58px;height:58px;
            border-radius:19px;
            display:grid;place-items:center;
            font-size:1.25rem;
            border:1px solid rgba(255,255,255,.10);
            background:linear-gradient(145deg,rgba(255,255,255,.10),rgba(255,255,255,.025));
            box-shadow:inset 0 1px 0 rgba(255,255,255,.08);
        }

        .v4-micro-bars {
            display:flex;
            align-items:end;
            gap:5px;
            height:72px;
        }
        .v4-micro-bars span {
            flex:1;
            border-radius:999px;
            min-width:4px;
            background:linear-gradient(to top,rgba(var(--v4-violet),.55),rgba(var(--v4-cyan),.95));
            transform-origin:bottom;
            animation:v4BarLive 2.5s ease-in-out infinite alternate;
        }
        @keyframes v4BarLive {
            from { transform:scaleY(.55);opacity:.55; }
            to { transform:scaleY(1);opacity:1; }
        }

        .v4-path {
            position:relative;
            padding:1.2rem;
            border-radius:2.2rem;
            border:1px solid rgba(255,255,255,.09);
            background:rgba(2,6,23,.42);
            overflow:hidden;
        }

        .v4-path-track {
            position:absolute;
            left:9%;
            right:9%;
            top:50%;
            height:1px;
            background:linear-gradient(90deg,rgba(var(--v4-cyan),.15),rgba(var(--v4-violet),.65),rgba(var(--v4-emerald),.30));
        }

        .v4-path-track::after {
            content:"";
            position:absolute;
            top:-2px;
            width:48px;height:5px;
            border-radius:999px;
            background:rgb(var(--v4-cyan));
            box-shadow:0 0 20px rgba(var(--v4-cyan),.8);
            animation:v4PathTravel 5s ease-in-out infinite;
        }

        @keyframes v4PathTravel {
            0% { left:0;opacity:0; }
            10% { opacity:1; }
            90% { opacity:1; }
            100% { left:calc(100% - 48px);opacity:0; }
        }

        .v4-path-grid {
            position:relative;
            z-index:2;
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:1rem;
        }

        .v4-path-node {
            text-align:center;
            padding:1rem .5rem;
        }

        .v4-path-node .bubble {
            width:70px;height:70px;
            margin:0 auto;
            border-radius:23px;
            display:grid;place-items:center;
            font-size:1.25rem;
            background:rgba(15,23,42,.92);
            border:1px solid rgba(var(--v4-cyan),.20);
            box-shadow:0 0 35px rgba(var(--v4-violet),.12),inset 0 1px 0 rgba(255,255,255,.06);
            transition:.35s ease;
        }

        .v4-path-node:hover .bubble {
            transform:translateY(-7px) scale(1.06);
            border-color:rgba(var(--v4-cyan),.42);
            box-shadow:0 0 50px rgba(var(--v4-cyan),.18);
        }

        .v4-marquee {
            overflow:hidden;
            border-top:1px solid rgba(255,255,255,.07);
            border-bottom:1px solid rgba(255,255,255,.07);
            background:rgba(255,255,255,.018);
            mask-image:linear-gradient(90deg,transparent,#000 10%,#000 90%,transparent);
        }

        .v4-marquee-track {
            width:max-content;
            display:flex;
            gap:1rem;
            padding:1rem 0;
            animation:v4Marquee 28s linear infinite;
        }

        .v4-marquee-item {
            display:flex;
            align-items:center;
            gap:.65rem;
            white-space:nowrap;
            padding:.7rem 1rem;
            border-radius:999px;
            border:1px solid rgba(255,255,255,.08);
            background:rgba(255,255,255,.035);
            color:rgb(203 213 225);
            font-size:.72rem;
            font-weight:800;
        }

        .v4-marquee-item i { color:rgb(var(--v4-cyan)); }

        @keyframes v4Marquee { to { transform:translateX(-50%); } }

        .v4-terminal {
            border-radius:2rem;
            overflow:hidden;
            border:1px solid rgba(255,255,255,.10);
            background:rgba(2,6,23,.76);
            box-shadow:0 35px 100px rgba(0,0,0,.38);
        }

        .v4-terminal-head {
            display:flex;align-items:center;justify-content:space-between;
            padding:1rem 1.15rem;
            border-bottom:1px solid rgba(255,255,255,.07);
            background:rgba(255,255,255,.025);
        }

        .v4-terminal-body { padding:1.3rem; }

        .v4-terminal-row {
            display:flex;
            align-items:center;
            gap:.8rem;
            padding:.85rem 0;
            border-bottom:1px solid rgba(255,255,255,.055);
            font-size:.78rem;
        }
        .v4-terminal-row:last-child { border-bottom:0; }

        .v4-terminal-dot {
            width:8px;height:8px;border-radius:50%;
            background:rgb(var(--v4-emerald));
            box-shadow:0 0 14px rgba(var(--v4-emerald),.8);
        }

        .v4-scan {
            position:absolute;
            inset:0;
            pointer-events:none;
            overflow:hidden;
        }

        .v4-scan::after {
            content:"";
            position:absolute;
            left:0;right:0;
            height:90px;
            background:linear-gradient(to bottom,transparent,rgba(var(--v4-cyan),.035),transparent);
            animation:v4Scanner 7s linear infinite;
        }

        @keyframes v4Scanner {
            from { transform:translateY(-120px); }
            to { transform:translateY(760px); }
        }

        .v4-magnetic { will-change:transform;transition:transform .18s ease-out; }

        .v4-action {
            position:relative;
            overflow:hidden;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:.7rem;
            padding:1rem 1.35rem;
            border-radius:1.2rem;
            font-weight:900;
            background:linear-gradient(100deg,rgb(6 182 212),rgb(79 70 229),rgb(192 38 211));
            box-shadow:0 18px 55px rgba(var(--v4-violet),.30);
            transition:box-shadow .3s ease,transform .3s ease;
        }

        .v4-action:hover {
            transform:translateY(-4px) scale(1.02);
            box-shadow:0 26px 75px rgba(var(--v4-violet),.42);
        }

        .v4-action::before {
            content:"";
            position:absolute;
            top:-60%;left:-40%;
            width:30%;height:220%;
            transform:rotate(24deg);
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.50),transparent);
            animation:v4ActionShine 3.8s ease-in-out infinite;
        }

        @keyframes v4ActionShine {
            0%,55% { left:-45%; }
            100% { left:130%; }
        }

        .v4-cursor-aura {
            position:fixed;
            width:360px;height:360px;
            margin:-180px 0 0 -180px;
            border-radius:50%;
            pointer-events:none;
            z-index:0;
            opacity:.16;
            filter:blur(70px);
            background:radial-gradient(circle,rgba(var(--v4-cyan),.65),rgba(var(--v4-violet),.28) 42%,transparent 70%);
            transition:opacity .25s ease;
            will-change:transform;
        }

        .v4-shooting-star {
            position:fixed;
            z-index:0;
            width:120px;height:1px;
            pointer-events:none;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.8));
            transform:rotate(-28deg);
            animation:v4StarFly var(--star-dur,8s) linear infinite;
            animation-delay:var(--star-delay,0s);
            opacity:0;
        }

        @keyframes v4StarFly {
            0% { transform:translate3d(-20vw,-20vh,0) rotate(-28deg);opacity:0; }
            8% { opacity:.5; }
            20% { opacity:0; }
            100% { transform:translate3d(120vw,120vh,0) rotate(-28deg);opacity:0; }
        }

        .v4-number {
            font-variant-numeric:tabular-nums;
        }

        @media (max-width:1023px) {
            .v4-holo-board { min-height:590px; }
            .v4-span-7,.v4-span-5 { grid-column:span 12; }
            .v4-span-4 { grid-column:span 6; }
        }

        @media (max-width:767px) {
            .v4-title { font-size:clamp(2.55rem,14vw,4.7rem);line-height:.93; }
            .v4-holo-board { min-height:520px;border-radius:2rem; }
            .v4-core { width:102px;height:102px;border-radius:30px; }
            .v4-core-ring.a { width:160px;height:160px; }
            .v4-core-ring.b { width:250px;height:250px; }
            .v4-core-ring.c { width:355px;height:355px; }
            .v4-data-pod { min-width:118px;padding:.65rem .7rem; }
            .v4-data-pod strong { font-size:.68rem; }
            .v4-data-pod small { font-size:.56rem; }
            .v4-pod-1 { left:3%;top:9%; }
            .v4-pod-2 { right:3%;top:13%; }
            .v4-pod-3 { left:3%;bottom:18%; }
            .v4-pod-4 { right:3%;bottom:17%; }
            .v4-live-strip { left:3%;right:3%;bottom:3%;gap:.35rem; }
            .v4-live-stat { padding:.65rem .55rem; }
            .v4-live-stat p:first-child { font-size:.48rem; }
            .v4-live-stat p:last-child { font-size:.92rem; }
            .v4-span-4 { grid-column:span 12; }
            .v4-path-track { display:none; }
            .v4-path-grid { grid-template-columns:repeat(2,1fr); }
        }

        @media (prefers-reduced-motion:reduce) {
            .v4-aurora,.v4-signal::after,.v4-liquid-text,.v4-holo-board::before,
            .v4-core,.v4-core-ring,.v4-data-pod,.v4-wave,.v4-micro-bars span,
            .v4-path-track::after,.v4-marquee-track,.v4-scan::after,.v4-action::before,
            .v4-shooting-star { animation:none !important; }
        }

    </style>

</head>


<body class="font-sans bg-slate-950 text-white overflow-x-hidden">

    <div id="uc-scroll-progress" class="uc-scroll-progress"></div>
    <div id="uc-cursor-glow"></div>

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
                uc-nav-premium
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
                        uc-brand-mark
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
                        Campus Network
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
            <div class="uc-hero-copy">


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
                            uc-status-dot
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
                            uc-gradient-title
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

            <div class="relative uc-hero-stage">

                <div class="uc-orbit one"><span class="uc-orbit-dot"></span></div>
                <div class="uc-orbit two"><span class="uc-orbit-dot"></span></div>
                <div class="uc-orbit three"></div>
                <div class="uc-float-chip chip-a"><span class="text-cyan-300 font-black text-xs">VERIFIED</span><div class="text-sm font-bold mt-1">Campus Identity</div></div>
                <div class="uc-float-chip chip-b"><span class="text-fuchsia-300 font-black text-xs">LIVE</span><div class="text-sm font-bold mt-1">Career Network</div></div>
                <div class="uc-float-chip chip-c"><span class="text-emerald-300 font-black text-xs">CONNECTED</span><div class="text-sm font-bold mt-1">Students + Alumni</div></div>

                <div
                    class="
                        uc-glass
                        rounded-[2rem]
                        p-6
                        uc-card
                    "
                >
                    <div class="uc-scan"></div>


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
                    uc-reveal
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



        <section class="max-w-7xl mx-auto px-6 pb-14 uc-reveal">
            <div class="uc-glass rounded-3xl p-4 uc-marquee-wrap border border-white/10">
                <div class="uc-marquee text-xs font-black uppercase tracking-[.24em] text-slate-400">
                    <span>Verified Identity</span><span class="text-cyan-300">✦</span><span>Student Network</span><span class="text-fuchsia-300">✦</span><span>Alumni Mentorship</span><span class="text-emerald-300">✦</span><span>Career Opportunities</span><span class="text-indigo-300">✦</span><span>Campus Communication</span><span class="text-cyan-300">✦</span>
                    <span>Verified Identity</span><span class="text-cyan-300">✦</span><span>Student Network</span><span class="text-fuchsia-300">✦</span><span>Alumni Mentorship</span><span class="text-emerald-300">✦</span><span>Career Opportunities</span><span class="text-indigo-300">✦</span><span>Campus Communication</span><span class="text-cyan-300">✦</span>
                </div>
            </div>
        </section>



        {{-- ===================================================== --}}
        {{-- DOUBLE ULTRA PRO — LIVE CAMPUS INTELLIGENCE --}}
        {{-- Existing project flow is unchanged; this is visual UI --}}
        {{-- ===================================================== --}}
        <section class="max-w-7xl mx-auto px-6 py-24 uc-reveal uc-cinematic-shell">
            <div class="grid grid-cols-1 lg:grid-cols-[.82fr_1.18fr] gap-10 items-center">
                <div class="relative z-10">
                    <span class="uc-section-kicker">Live Campus Intelligence</span>

                    <h3 class="uc-mega-title mt-6">
                        One network.
                        <span class="block uc-prism-text">Every connection alive.</span>
                    </h3>

                    <p class="mt-6 text-slate-400 leading-8 max-w-xl">
                        University Connect brings verified identity, student networking,
                        alumni mentorship, career opportunities and university communication
                        into one connected digital environment.
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="uc-mini-metric">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[.18em] text-slate-500 font-black">Students</p>
                                    <p class="text-3xl font-black mt-2 text-cyan-200">{{ $homeStats['students'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-user-graduate text-cyan-300 text-xl"></i>
                            </div>
                            <div class="uc-spark mt-5">
                                @foreach ([35,58,42,70,55,88,62,95,72,100] as $i => $h)
                                    <span style="height: {{ $h }}%; animation-delay: -{{ $i * 0.12 }}s"></span>
                                @endforeach
                            </div>
                        </div>

                        <div class="uc-mini-metric">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[.18em] text-slate-500 font-black">Alumni</p>
                                    <p class="text-3xl font-black mt-2 text-fuchsia-200">{{ $homeStats['alumni'] ?? 0 }}</p>
                                </div>
                                <i class="fas fa-award text-fuchsia-300 text-xl"></i>
                            </div>
                            <div class="uc-spark mt-5">
                                @foreach ([42,65,50,78,61,90,74,84,96,88] as $i => $h)
                                    <span style="height: {{ $h }}%; animation-delay: -{{ $i * 0.14 }}s"></span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 uc-command-center p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-11 w-11 rounded-2xl bg-emerald-500/10 border border-emerald-400/15 grid place-items-center text-emerald-300">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                                <div>
                                    <p class="font-black">Verified Access Layer</p>
                                    <p class="text-xs text-slate-500 mt-1">University-provided identity workflow</p>
                                </div>
                            </div>
                            <span class="uc-status-pill">ONLINE</span>
                        </div>
                    </div>
                </div>

                <div class="uc-network-visual">
                    <canvas id="uc-network-canvas"></canvas>

                    <div class="uc-network-core">
                        <div class="text-center">
                            <i class="fas fa-graduation-cap text-3xl text-cyan-200"></i>
                            <p class="text-[10px] font-black tracking-[.18em] mt-2 text-white">UC CORE</p>
                        </div>
                    </div>

                    <div class="uc-node-label uc-node-1">
                        <strong><i class="fas fa-user-graduate"></i>Students</strong>
                        Verified campus identity
                    </div>

                    <div class="uc-node-label uc-node-2">
                        <strong><i class="fas fa-user-tie"></i>Alumni</strong>
                        Mentorship & network
                    </div>

                    <div class="uc-node-label uc-node-3">
                        <strong><i class="fas fa-briefcase"></i>Career</strong>
                        Jobs & internships
                    </div>

                    <div class="uc-node-label uc-node-4">
                        <strong><i class="fas fa-building-columns"></i>Admin</strong>
                        Connected management
                    </div>

                    <span class="uc-holo-line" style="left:8%;top:35%;"></span>
                    <span class="uc-holo-line" style="right:8%;bottom:35%;animation-delay:-3s;"></span>
                </div>
            </div>
        </section>


        {{-- ===================================================== --}}
        {{-- ROLE EXPERIENCE --}}
        {{-- ===================================================== --}}
        <section class="max-w-7xl mx-auto px-6 py-20 uc-reveal">
            <div class="text-center max-w-4xl mx-auto">
                <span class="uc-section-kicker">Connected Experience</span>
                <h3 class="uc-mega-title mt-6">
                    Built around the
                    <span class="uc-prism-text">university community.</span>
                </h3>
                <p class="mt-6 text-slate-400 leading-8 max-w-2xl mx-auto">
                    Each role stays connected to the same University Connect ecosystem
                    while keeping its own purpose, access and workflow.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">
                <article class="uc-role-card uc-tilt" style="--role-glow:rgba(34,211,238,.25)">
                    <div class="uc-role-icon text-cyan-300"><i class="fas fa-user-graduate"></i></div>
                    <p class="text-[10px] tracking-[.22em] uppercase font-black text-cyan-300 mt-7">01 / Student</p>
                    <h4 class="text-2xl font-black mt-2">Discover & Connect</h4>
                    <p class="text-sm text-slate-400 leading-6 mt-3">
                        Access the verified network, connect with alumni and discover relevant opportunities.
                    </p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-bold text-slate-300">
                        <span class="h-2 w-2 rounded-full bg-cyan-300 shadow-[0_0_12px_rgba(103,232,249,.8)]"></span>
                        Verified university access
                    </div>
                </article>

                <article class="uc-role-card uc-tilt" style="--role-glow:rgba(217,70,239,.24)">
                    <div class="uc-role-icon text-fuchsia-300"><i class="fas fa-user-tie"></i></div>
                    <p class="text-[10px] tracking-[.22em] uppercase font-black text-fuchsia-300 mt-7">02 / Alumni</p>
                    <h4 class="text-2xl font-black mt-2">Guide & Empower</h4>
                    <p class="text-sm text-slate-400 leading-6 mt-3">
                        Support students through mentorship, professional networking and shared opportunities.
                    </p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-bold text-slate-300">
                        <span class="h-2 w-2 rounded-full bg-fuchsia-300 shadow-[0_0_12px_rgba(240,171,252,.8)]"></span>
                        Alumni mentorship network
                    </div>
                </article>

                <article class="uc-role-card uc-tilt" style="--role-glow:rgba(16,185,129,.24)">
                    <div class="uc-role-icon text-emerald-300"><i class="fas fa-briefcase"></i></div>
                    <p class="text-[10px] tracking-[.22em] uppercase font-black text-emerald-300 mt-7">03 / Career</p>
                    <h4 class="text-2xl font-black mt-2">Grow & Progress</h4>
                    <p class="text-sm text-slate-400 leading-6 mt-3">
                        Bring jobs, internships and professional connections into the same campus ecosystem.
                    </p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-bold text-slate-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-300 shadow-[0_0_12px_rgba(110,231,183,.8)]"></span>
                        Opportunity discovery
                    </div>
                </article>

                <article class="uc-role-card uc-tilt" style="--role-glow:rgba(99,102,241,.28)">
                    <div class="uc-role-icon text-indigo-300"><i class="fas fa-user-shield"></i></div>
                    <p class="text-[10px] tracking-[.22em] uppercase font-black text-indigo-300 mt-7">04 / Admin</p>
                    <h4 class="text-2xl font-black mt-2">Manage & Coordinate</h4>
                    <p class="text-sm text-slate-400 leading-6 mt-3">
                        Manage verified users, platform activities, notices, events and university services.
                    </p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-bold text-slate-300">
                        <span class="h-2 w-2 rounded-full bg-indigo-300 shadow-[0_0_12px_rgba(165,180,252,.8)]"></span>
                        Centralized administration
                    </div>
                </article>
            </div>
        </section>


        {{-- ===================================================== --}}
        {{-- PROCESS FLOW VISUAL --}}
        {{-- ===================================================== --}}
        <section class="max-w-7xl mx-auto px-6 py-24 uc-reveal">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="uc-section-kicker">Experience Flow</span>
                    <h3 class="uc-mega-title mt-6">
                        Simple access.
                        <span class="block uc-prism-text">Connected journey.</span>
                    </h3>
                    <p class="mt-6 text-slate-400 leading-8 max-w-xl">
                        The existing University Connect access model remains unchanged.
                        The interface simply presents that experience with a more cinematic,
                        modern and premium visual system.
                    </p>

                    <div class="uc-flow-rail mt-8">
                        <div class="uc-flow-step">
                            <div class="uc-flow-index">01</div>
                            <div class="uc-flow-content">
                                <p class="font-black">Verified identity</p>
                                <p class="text-sm text-slate-400 mt-1 leading-6">
                                    Students and Alumni use their university-provided verified account information.
                                </p>
                            </div>
                        </div>

                        <div class="uc-flow-step">
                            <div class="uc-flow-index">02</div>
                            <div class="uc-flow-content">
                                <p class="font-black">Secure login</p>
                                <p class="text-sm text-slate-400 mt-1 leading-6">
                                    Existing login and dashboard routing continue to work exactly as before.
                                </p>
                            </div>
                        </div>

                        <div class="uc-flow-step">
                            <div class="uc-flow-index">03</div>
                            <div class="uc-flow-content">
                                <p class="font-black">Connected ecosystem</p>
                                <p class="text-sm text-slate-400 mt-1 leading-6">
                                    Users move into the platform experience for networking, mentorship and opportunities.
                                </p>
                            </div>
                        </div>

                        <div class="uc-flow-step">
                            <div class="uc-flow-index">04</div>
                            <div class="uc-flow-content">
                                <p class="font-black">Continuous engagement</p>
                                <p class="text-sm text-slate-400 mt-1 leading-6">
                                    Career, communication and university activities remain connected in one environment.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uc-orbit-console">
                    <div class="uc-orbit-ring r1"></div>
                    <div class="uc-orbit-ring r2"></div>
                    <div class="uc-orbit-ring r3"></div>

                    <div class="uc-orbit-avatar">
                        <i class="fas fa-graduation-cap text-cyan-200"></i>
                    </div>

                    <div class="uc-float-chip uc-chip-a"><i class="fas fa-shield-halved text-emerald-300 mr-2"></i>Verified</div>
                    <div class="uc-float-chip uc-chip-b"><i class="fas fa-users text-cyan-300 mr-2"></i>Network</div>
                    <div class="uc-float-chip uc-chip-c"><i class="fas fa-handshake-angle text-fuchsia-300 mr-2"></i>Mentorship</div>
                    <div class="uc-float-chip uc-chip-d"><i class="fas fa-briefcase text-emerald-300 mr-2"></i>Career</div>
                </div>
            </div>
        </section>


        {{-- ===================================================== --}}
        {{-- PREMIUM ACCESS CTA --}}
        {{-- ===================================================== --}}
        <section class="max-w-7xl mx-auto px-6 py-20 uc-reveal">
            <div class="uc-cta-ultra p-8 md:p-12 lg:p-14">
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-10 items-center">
                    <div>
                        <span class="uc-section-kicker">University Connect</span>
                        <h3 class="text-4xl md:text-6xl font-black tracking-[-.045em] leading-[1.02] mt-6">
                            Your campus network,
                            <span class="uc-prism-text">reimagined.</span>
                        </h3>
                        <p class="text-slate-400 leading-7 mt-5 max-w-2xl">
                            A verified digital bridge between students, alumni and university administration.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="uc-primary-cta uc-magnetic">
                                <i class="fas fa-table-columns"></i>
                                Open Dashboard
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="uc-primary-cta uc-magnetic">
                                <i class="fas fa-right-to-bracket"></i>
                                Enter University Connect
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        @endauth

                        <div class="flex items-center justify-center gap-2 text-xs text-slate-500 font-bold px-3">
                            <i class="fas fa-shield-halved text-emerald-300"></i>
                            Verified access environment
                        </div>
                    </div>
                </div>
            </div>
        </section>



        {{-- ===================================================== --}}
        {{-- 4X ULTRA PREMIUM LIVE EXPERIENCE --}}
        {{-- Visual upgrade only — Laravel process remains same    --}}
        {{-- ===================================================== --}}
        <section class="max-w-7xl mx-auto px-6 pt-24 pb-16 v4-stage uc-reveal">
            <div class="v4-aurora"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-[.82fr_1.18fr] gap-12 items-center">
                <div>
                    <span class="v4-kicker">
                        <span class="v4-signal"></span>
                        Campus Network / Live
                    </span>

                    <h3 class="v4-title mt-7">
                        The campus
                        <span class="block v4-liquid-text">feels alive.</span>
                    </h3>

                    <p class="mt-7 text-lg text-slate-400 leading-8 max-w-xl">
                        A cinematic view of the same University Connect ecosystem:
                        verified students, alumni mentorship, career opportunities
                        and university administration — connected in one digital network.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-8">
                        <span class="px-4 py-2 rounded-full border border-cyan-300/10 bg-cyan-300/5 text-xs font-black text-cyan-200">
                            <i class="fas fa-shield-halved mr-2"></i>Verified Access
                        </span>
                        <span class="px-4 py-2 rounded-full border border-fuchsia-300/10 bg-fuchsia-300/5 text-xs font-black text-fuchsia-200">
                            <i class="fas fa-handshake-angle mr-2"></i>Mentorship
                        </span>
                        <span class="px-4 py-2 rounded-full border border-emerald-300/10 bg-emerald-300/5 text-xs font-black text-emerald-200">
                            <i class="fas fa-briefcase mr-2"></i>Career
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-8">
                        <div class="v4-glass rounded-2xl p-4">
                            <p class="text-[10px] uppercase tracking-[.18em] font-black text-slate-500">Students</p>
                            <p class="v4-number text-2xl font-black text-cyan-200 mt-2">{{ $homeStats['students'] ?? 0 }}</p>
                        </div>
                        <div class="v4-glass rounded-2xl p-4">
                            <p class="text-[10px] uppercase tracking-[.18em] font-black text-slate-500">Alumni</p>
                            <p class="v4-number text-2xl font-black text-fuchsia-200 mt-2">{{ $homeStats['alumni'] ?? 0 }}</p>
                        </div>
                        <div class="v4-glass rounded-2xl p-4">
                            <p class="text-[10px] uppercase tracking-[.18em] font-black text-slate-500">Jobs</p>
                            <p class="v4-number text-2xl font-black text-emerald-200 mt-2">{{ $homeStats['jobs'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="v4-holo-board" id="v4-holo-board">
                    <canvas id="v4-constellation"></canvas>
                    <div class="v4-scan"></div>
                    <div class="v4-wave w1"></div>
                    <div class="v4-wave w2"></div>
                    <div class="v4-wave w3"></div>

                    <div class="v4-core-ring a"></div>
                    <div class="v4-core-ring b"></div>
                    <div class="v4-core-ring c"></div>

                    <div class="v4-core">
                        <div class="text-center">
                            <i class="fas fa-graduation-cap text-4xl text-cyan-100"></i>
                            <p class="mt-2 text-[9px] tracking-[.24em] font-black">UC / CORE</p>
                        </div>
                    </div>

                    <div class="v4-data-pod v4-pod-1">
                        <strong><i class="fas fa-user-graduate"></i>Student Layer</strong>
                        <small>Verified campus network</small>
                    </div>
                    <div class="v4-data-pod v4-pod-2">
                        <strong><i class="fas fa-user-tie"></i>Alumni Layer</strong>
                        <small>Mentorship connection</small>
                    </div>
                    <div class="v4-data-pod v4-pod-3">
                        <strong><i class="fas fa-briefcase"></i>Career Layer</strong>
                        <small>Jobs & internships</small>
                    </div>
                    <div class="v4-data-pod v4-pod-4">
                        <strong><i class="fas fa-user-shield"></i>Admin Layer</strong>
                        <small>Platform management</small>
                    </div>

                    <div class="v4-live-strip">
                        <div class="v4-live-stat">
                            <p>Students</p>
                            <p>{{ $homeStats['students'] ?? 0 }}</p>
                        </div>
                        <div class="v4-live-stat">
                            <p>Alumni</p>
                            <p>{{ $homeStats['alumni'] ?? 0 }}</p>
                        </div>
                        <div class="v4-live-stat">
                            <p>Opportunities</p>
                            <p>{{ $homeStats['jobs'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MOVING CAPABILITY STRIP --}}
        <div class="v4-marquee my-10">
            <div class="v4-marquee-track">
                @foreach ([1,2] as $loopCopy)
                    <span class="v4-marquee-item"><i class="fas fa-shield-halved"></i> Verified University Access</span>
                    <span class="v4-marquee-item"><i class="fas fa-user-graduate"></i> Student Network</span>
                    <span class="v4-marquee-item"><i class="fas fa-user-tie"></i> Alumni Community</span>
                    <span class="v4-marquee-item"><i class="fas fa-handshake-angle"></i> Mentorship</span>
                    <span class="v4-marquee-item"><i class="fas fa-briefcase"></i> Career Opportunities</span>
                    <span class="v4-marquee-item"><i class="fas fa-calendar-days"></i> Campus Events</span>
                    <span class="v4-marquee-item"><i class="fas fa-bullhorn"></i> University Communication</span>
                @endforeach
            </div>
        </div>

        {{-- PREMIUM BENTO SYSTEM --}}
        <section class="max-w-7xl mx-auto px-6 py-24 uc-reveal">
            <div class="max-w-4xl">
                <span class="v4-kicker"><span class="v4-signal"></span>Connected Ecosystem</span>
                <h3 class="text-5xl md:text-7xl font-black tracking-[-.055em] leading-[.95] mt-7">
                    Designed as one
                    <span class="v4-liquid-text">living system.</span>
                </h3>
                <p class="mt-6 text-slate-400 leading-8 max-w-2xl">
                    The visual system changes dramatically, while the University Connect
                    roles, authentication and project functionality remain the same.
                </p>
            </div>

            <div class="v4-feature-bento mt-12">
                <article class="v4-bento v4-span-7 v4-tilt" style="--v4-card-glow:rgba(34,211,238,.16)">
                    <div class="relative z-10 h-full flex flex-col">
                        <div class="flex items-start justify-between gap-5">
                            <div class="v4-icon-box text-cyan-200"><i class="fas fa-users-viewfinder"></i></div>
                            <span class="text-[10px] uppercase tracking-[.2em] font-black text-cyan-300">Network / 01</span>
                        </div>
                        <div class="mt-auto pt-12">
                            <h4 class="text-3xl md:text-4xl font-black">Verified Campus Network</h4>
                            <p class="mt-3 text-slate-400 leading-7 max-w-xl">
                                Students and Alumni stay connected through university-provided verified account access.
                            </p>
                        </div>
                    </div>
                </article>

                <article class="v4-bento v4-span-5 v4-tilt" style="--v4-card-glow:rgba(217,70,239,.16)">
                    <div class="relative z-10">
                        <div class="v4-icon-box text-fuchsia-200"><i class="fas fa-handshake-angle"></i></div>
                        <h4 class="text-3xl font-black mt-8">Mentorship</h4>
                        <p class="mt-3 text-slate-400 leading-7">
                            Connect students with verified alumni for academic and professional guidance.
                        </p>
                        <div class="mt-8 flex -space-x-3">
                            @foreach (['S','A','M','C'] as $letter)
                                <div class="h-11 w-11 rounded-full border-2 border-slate-950 bg-gradient-to-br from-indigo-500/80 to-fuchsia-500/80 grid place-items-center text-xs font-black">
                                    {{ $letter }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>

                <article class="v4-bento v4-span-4 v4-tilt" style="--v4-card-glow:rgba(16,185,129,.16)">
                    <div class="relative z-10">
                        <div class="v4-icon-box text-emerald-200"><i class="fas fa-briefcase"></i></div>
                        <h4 class="text-2xl font-black mt-7">Career Portal</h4>
                        <p class="mt-3 text-sm text-slate-400 leading-6">
                            Jobs, internships and professional opportunities within the connected community.
                        </p>
                    </div>
                </article>

                <article class="v4-bento v4-span-4 v4-tilt" style="--v4-card-glow:rgba(99,102,241,.18)">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="v4-icon-box text-indigo-200"><i class="fas fa-chart-line"></i></div>
                            <span class="text-[10px] font-black text-emerald-300">LIVE</span>
                        </div>
                        <h4 class="text-2xl font-black mt-7">Growth Activity</h4>
                        <div class="v4-micro-bars mt-7">
                            @foreach ([38,65,49,82,58,91,70,100,77,94,68,88] as $i => $height)
                                <span style="height:{{ $height }}%;animation-delay:-{{ $i * .13 }}s"></span>
                            @endforeach
                        </div>
                    </div>
                </article>

                <article class="v4-bento v4-span-4 v4-tilt" style="--v4-card-glow:rgba(244,114,182,.14)">
                    <div class="relative z-10">
                        <div class="v4-icon-box text-pink-200"><i class="fas fa-user-shield"></i></div>
                        <h4 class="text-2xl font-black mt-7">Administration</h4>
                        <p class="mt-3 text-sm text-slate-400 leading-6">
                            Centralized management of verified users, activities, events, notices and services.
                        </p>
                    </div>
                </article>
            </div>
        </section>

        {{-- FLOW PATH --}}
        <section class="max-w-7xl mx-auto px-6 py-20 uc-reveal">
            <div class="text-center max-w-4xl mx-auto">
                <span class="v4-kicker"><span class="v4-signal"></span>Same Process / New Experience</span>
                <h3 class="text-5xl md:text-7xl font-black tracking-[-.055em] leading-[.95] mt-7">
                    The workflow stays
                    <span class="v4-liquid-text">exactly connected.</span>
                </h3>
            </div>

            <div class="v4-path mt-12">
                <div class="v4-path-track"></div>
                <div class="v4-path-grid">
                    <div class="v4-path-node">
                        <div class="bubble text-cyan-200"><i class="fas fa-id-card"></i></div>
                        <p class="font-black mt-4">Verified Identity</p>
                        <p class="text-xs text-slate-500 mt-1">University account</p>
                    </div>
                    <div class="v4-path-node">
                        <div class="bubble text-indigo-200"><i class="fas fa-right-to-bracket"></i></div>
                        <p class="font-black mt-4">Login</p>
                        <p class="text-xs text-slate-500 mt-1">Existing auth flow</p>
                    </div>
                    <div class="v4-path-node">
                        <div class="bubble text-fuchsia-200"><i class="fas fa-diagram-project"></i></div>
                        <p class="font-black mt-4">Connect</p>
                        <p class="text-xs text-slate-500 mt-1">Student & Alumni</p>
                    </div>
                    <div class="v4-path-node">
                        <div class="bubble text-emerald-200"><i class="fas fa-arrow-trend-up"></i></div>
                        <p class="font-black mt-4">Grow</p>
                        <p class="text-xs text-slate-500 mt-1">Mentorship & career</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- LIVE SYSTEM TERMINAL + CTA --}}
        <section class="max-w-7xl mx-auto px-6 py-24 uc-reveal">
            <div class="grid grid-cols-1 lg:grid-cols-[.9fr_1.1fr] gap-8 items-stretch">
                <div class="v4-terminal">
                    <div class="v4-terminal-head">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        </div>
                        <span class="text-[10px] tracking-[.2em] uppercase font-black text-slate-500">University Connect / System</span>
                    </div>
                    <div class="v4-terminal-body">
                        <div class="v4-terminal-row">
                            <span class="v4-terminal-dot"></span>
                            <span class="text-slate-500">Identity layer</span>
                            <span class="ml-auto text-emerald-300 font-black">VERIFIED</span>
                        </div>
                        <div class="v4-terminal-row">
                            <span class="v4-terminal-dot"></span>
                            <span class="text-slate-500">Student network</span>
                            <span class="ml-auto text-cyan-300 font-black">{{ $homeStats['students'] ?? 0 }}</span>
                        </div>
                        <div class="v4-terminal-row">
                            <span class="v4-terminal-dot"></span>
                            <span class="text-slate-500">Alumni network</span>
                            <span class="ml-auto text-fuchsia-300 font-black">{{ $homeStats['alumni'] ?? 0 }}</span>
                        </div>
                        <div class="v4-terminal-row">
                            <span class="v4-terminal-dot"></span>
                            <span class="text-slate-500">Career opportunities</span>
                            <span class="ml-auto text-emerald-300 font-black">{{ $homeStats['jobs'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="v4-glass rounded-[2rem] p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-fuchsia-500/10 blur-3xl"></div>
                    <div class="absolute -left-16 -bottom-20 h-64 w-64 rounded-full bg-cyan-500/10 blur-3xl"></div>
                    <div class="relative z-10 h-full flex flex-col justify-center">
                        <span class="v4-kicker w-fit"><span class="v4-signal"></span>Verified Campus Network</span>
                        <h3 class="text-4xl md:text-6xl font-black tracking-[-.05em] leading-[1] mt-6">
                            Enter the
                            <span class="v4-liquid-text">University Connect.</span>
                        </h3>
                        <p class="mt-5 text-slate-400 leading-7 max-w-xl">
                            Same project functionality. A completely elevated, cinematic interface.
                        </p>

                        <div class="mt-8">
                            @auth
                                <a href="{{ route('dashboard') }}" class="v4-action v4-magnetic">
                                    <i class="fas fa-table-columns"></i>
                                    Open Dashboard
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="v4-action v4-magnetic">
                                    <i class="fas fa-right-to-bracket"></i>
                                    Login to University Connect
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
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


    <script>
        (() => {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const glow = document.getElementById('uc-cursor-glow');
            const progress = document.getElementById('uc-scroll-progress');
            const stage = document.querySelector('.uc-hero-stage');
            const dashboard = document.querySelector('.uc-dashboard-3d');

            if (!reduceMotion && glow) {
                window.addEventListener('pointermove', (e) => {
                    glow.style.left = e.clientX + 'px';
                    glow.style.top = e.clientY + 'px';
                }, { passive: true });
            }

            const updateProgress = () => {
                const max = document.documentElement.scrollHeight - window.innerHeight;
                const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
                if (progress) progress.style.width = pct + '%';
            };
            updateProgress();
            window.addEventListener('scroll', updateProgress, { passive: true });

            if (!reduceMotion && stage && dashboard && window.innerWidth > 1024) {
                stage.addEventListener('pointermove', (e) => {
                    const r = stage.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width - .5;
                    const y = (e.clientY - r.top) / r.height - .5;
                    dashboard.style.transform = `rotateY(${x * 10}deg) rotateX(${-y * 8}deg) translateY(-4px)`;
                });
                stage.addEventListener('pointerleave', () => {
                    dashboard.style.transform = 'rotateY(0deg) rotateX(0deg) translateY(0)';
                });
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: .12 });
            document.querySelectorAll('.uc-reveal').forEach(el => observer.observe(el));

            if (!reduceMotion) {
                document.querySelectorAll('.uc-module-premium').forEach(card => {
                    card.addEventListener('pointermove', (e) => {
                        if (window.innerWidth <= 1024) return;
                        const r = card.getBoundingClientRect();
                        const x = (e.clientX - r.left) / r.width - .5;
                        const y = (e.clientY - r.top) / r.height - .5;
                        card.style.transform = `translateY(-10px) rotateY(${x * 5}deg) rotateX(${-y * 5}deg)`;
                    });
                    card.addEventListener('pointerleave', () => card.style.transform = '');
                });
            }
        })();
    </script>


    <script>
        (() => {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            /* Animated connected-campus canvas */
            const canvas = document.getElementById('uc-network-canvas');
            if (canvas && !reduceMotion) {
                const ctx = canvas.getContext('2d');
                let w = 0, h = 0, dpr = Math.min(window.devicePixelRatio || 1, 2);
                let particles = [];
                let raf = null;

                const resize = () => {
                    const rect = canvas.getBoundingClientRect();
                    w = rect.width;
                    h = rect.height;
                    canvas.width = Math.floor(w * dpr);
                    canvas.height = Math.floor(h * dpr);
                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

                    const count = Math.max(34, Math.min(72, Math.floor(w / 9)));
                    particles = Array.from({ length: count }, (_, i) => ({
                        x: Math.random() * w,
                        y: Math.random() * h,
                        vx: (Math.random() - .5) * .32,
                        vy: (Math.random() - .5) * .32,
                        r: Math.random() * 1.6 + .7,
                        phase: Math.random() * Math.PI * 2
                    }));
                };

                const draw = (time = 0) => {
                    ctx.clearRect(0, 0, w, h);

                    const cx = w / 2;
                    const cy = h / 2;

                    particles.forEach((p, i) => {
                        p.x += p.vx;
                        p.y += p.vy;
                        if (p.x < -10 || p.x > w + 10) p.vx *= -1;
                        if (p.y < -10 || p.y > h + 10) p.vy *= -1;

                        const pulse = .55 + Math.sin(time * .0015 + p.phase) * .28;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fillStyle = `rgba(165,243,252,${Math.max(.16, pulse)})`;
                        ctx.fill();

                        const dc = Math.hypot(p.x - cx, p.y - cy);
                        if (dc < Math.min(w, h) * .42) {
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(cx, cy);
                            ctx.strokeStyle = `rgba(99,102,241,${(1 - dc / (Math.min(w,h)*.42)) * .11})`;
                            ctx.lineWidth = .7;
                            ctx.stroke();
                        }

                        for (let j = i + 1; j < particles.length; j++) {
                            const q = particles[j];
                            const d = Math.hypot(p.x - q.x, p.y - q.y);
                            if (d < 82) {
                                ctx.beginPath();
                                ctx.moveTo(p.x, p.y);
                                ctx.lineTo(q.x, q.y);
                                ctx.strokeStyle = `rgba(103,232,249,${(1 - d / 82) * .12})`;
                                ctx.lineWidth = .55;
                                ctx.stroke();
                            }
                        }
                    });

                    const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, 150);
                    g.addColorStop(0, 'rgba(99,102,241,.14)');
                    g.addColorStop(1, 'rgba(99,102,241,0)');
                    ctx.fillStyle = g;
                    ctx.beginPath();
                    ctx.arc(cx, cy, 150, 0, Math.PI * 2);
                    ctx.fill();

                    raf = requestAnimationFrame(draw);
                };

                resize();
                draw();
                window.addEventListener('resize', resize, { passive: true });
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden && raf) cancelAnimationFrame(raf);
                    if (!document.hidden) draw();
                });
            }

            /* Premium 3D tilt cards */
            if (!reduceMotion && window.matchMedia('(pointer:fine)').matches) {
                document.querySelectorAll('.uc-tilt').forEach(card => {
                    card.addEventListener('pointermove', e => {
                        const r = card.getBoundingClientRect();
                        const x = (e.clientX - r.left) / r.width - .5;
                        const y = (e.clientY - r.top) / r.height - .5;
                        card.style.transform = `translateY(-10px) rotateX(${-y * 5}deg) rotateY(${x * 7}deg)`;
                    });
                    card.addEventListener('pointerleave', () => {
                        card.style.transform = '';
                    });
                });

                /* Magnetic CTA movement */
                document.querySelectorAll('.uc-magnetic').forEach(el => {
                    el.addEventListener('pointermove', e => {
                        const r = el.getBoundingClientRect();
                        const x = e.clientX - (r.left + r.width / 2);
                        const y = e.clientY - (r.top + r.height / 2);
                        el.style.transform = `translate(${x * .08}px, ${y * .12}px) translateY(-3px) scale(1.02)`;
                    });
                    el.addEventListener('pointerleave', () => {
                        el.style.transform = '';
                    });
                });
            }

            /* Decorative depth particles */
            if (!reduceMotion && window.innerWidth > 768) {
                const fragment = document.createDocumentFragment();
                for (let i = 0; i < 22; i++) {
                    const p = document.createElement('span');
                    p.className = 'uc-depth-particle';
                    p.style.left = `${Math.random() * 100}%`;
                    p.style.setProperty('--dur', `${12 + Math.random() * 14}s`);
                    p.style.setProperty('--delay', `${-Math.random() * 20}s`);
                    p.style.setProperty('--drift', `${-50 + Math.random() * 100}px`);
                    p.style.opacity = (.10 + Math.random() * .22).toFixed(2);
                    fragment.appendChild(p);
                }
                document.body.appendChild(fragment);

                const beam = document.createElement('div');
                beam.className = 'uc-live-beam';
                document.body.appendChild(beam);
            }
        })();
    </script>


    <script>
        (() => {
            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const fine = window.matchMedia('(pointer:fine)').matches;

            /* 4X constellation engine */
            const canvas = document.getElementById('v4-constellation');
            if (canvas && !reduce) {
                const ctx = canvas.getContext('2d');
                let W=0,H=0,dpr=Math.min(devicePixelRatio||1,2),points=[],mouse={x:-9999,y:-9999};
                const board = document.getElementById('v4-holo-board');

                function resize(){
                    const r=canvas.getBoundingClientRect();
                    W=r.width; H=r.height;
                    canvas.width=Math.floor(W*dpr);
                    canvas.height=Math.floor(H*dpr);
                    ctx.setTransform(dpr,0,0,dpr,0,0);
                    const n=Math.max(42,Math.min(86,Math.floor(W/8)));
                    points=Array.from({length:n},()=>({
                        x:Math.random()*W,y:Math.random()*H,
                        vx:(Math.random()-.5)*.34,vy:(Math.random()-.5)*.34,
                        r:.6+Math.random()*1.45,
                        phase:Math.random()*Math.PI*2
                    }));
                }

                if(board){
                    board.addEventListener('pointermove',e=>{
                        const r=board.getBoundingClientRect();
                        mouse.x=e.clientX-r.left;mouse.y=e.clientY-r.top;
                    });
                    board.addEventListener('pointerleave',()=>{mouse.x=-9999;mouse.y=-9999;});
                }

                function render(t=0){
                    ctx.clearRect(0,0,W,H);
                    const cx=W/2,cy=H*.47;

                    points.forEach((p,i)=>{
                        p.x+=p.vx;p.y+=p.vy;
                        if(p.x<0||p.x>W)p.vx*=-1;
                        if(p.y<0||p.y>H)p.vy*=-1;

                        const md=Math.hypot(p.x-mouse.x,p.y-mouse.y);
                        if(md<110){
                            p.x+=(p.x-mouse.x)*.003;
                            p.y+=(p.y-mouse.y)*.003;
                        }

                        const pulse=.38+.26*Math.sin(t*.0016+p.phase);
                        ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
                        ctx.fillStyle=`rgba(207,250,254,${Math.max(.12,pulse)})`;ctx.fill();

                        const dc=Math.hypot(p.x-cx,p.y-cy);
                        if(dc<230){
                            ctx.beginPath();ctx.moveTo(p.x,p.y);ctx.lineTo(cx,cy);
                            ctx.strokeStyle=`rgba(129,140,248,${(1-dc/230)*.10})`;
                            ctx.lineWidth=.6;ctx.stroke();
                        }

                        for(let j=i+1;j<points.length;j++){
                            const q=points[j],d=Math.hypot(p.x-q.x,p.y-q.y);
                            if(d<78){
                                ctx.beginPath();ctx.moveTo(p.x,p.y);ctx.lineTo(q.x,q.y);
                                ctx.strokeStyle=`rgba(103,232,249,${(1-d/78)*.11})`;
                                ctx.lineWidth=.5;ctx.stroke();
                            }
                        }
                    });

                    const rg=ctx.createRadialGradient(cx,cy,0,cx,cy,190);
                    rg.addColorStop(0,'rgba(99,102,241,.13)');
                    rg.addColorStop(.45,'rgba(34,211,238,.035)');
                    rg.addColorStop(1,'rgba(2,6,23,0)');
                    ctx.fillStyle=rg;ctx.beginPath();ctx.arc(cx,cy,190,0,Math.PI*2);ctx.fill();
                    requestAnimationFrame(render);
                }
                resize();render();
                addEventListener('resize',resize,{passive:true});
            }

            /* Card tilt + board perspective */
            if(!reduce && fine){
                document.querySelectorAll('.v4-tilt').forEach(el=>{
                    el.addEventListener('pointermove',e=>{
                        const r=el.getBoundingClientRect();
                        const x=(e.clientX-r.left)/r.width-.5;
                        const y=(e.clientY-r.top)/r.height-.5;
                        el.style.transform=`translateY(-8px) rotateX(${-y*4}deg) rotateY(${x*6}deg)`;
                    });
                    el.addEventListener('pointerleave',()=>el.style.transform='');
                });

                const board=document.getElementById('v4-holo-board');
                if(board){
                    board.addEventListener('pointermove',e=>{
                        const r=board.getBoundingClientRect();
                        const x=(e.clientX-r.left)/r.width-.5;
                        const y=(e.clientY-r.top)/r.height-.5;
                        board.style.transform=`perspective(1200px) rotateX(${-y*2.4}deg) rotateY(${x*3.2}deg)`;
                    });
                    board.addEventListener('pointerleave',()=>board.style.transform='');
                }

                document.querySelectorAll('.v4-magnetic').forEach(el=>{
                    el.addEventListener('pointermove',e=>{
                        const r=el.getBoundingClientRect();
                        const x=e.clientX-(r.left+r.width/2);
                        const y=e.clientY-(r.top+r.height/2);
                        el.style.transform=`translate(${x*.09}px,${y*.13}px) translateY(-4px) scale(1.02)`;
                    });
                    el.addEventListener('pointerleave',()=>el.style.transform='');
                });

                const aura=document.createElement('div');
                aura.className='v4-cursor-aura';
                document.body.appendChild(aura);
                let tx=innerWidth/2,ty=innerHeight/2,cx=tx,cy=ty;
                addEventListener('pointermove',e=>{tx=e.clientX;ty=e.clientY;},{passive:true});
                (function auraLoop(){
                    cx+=(tx-cx)*.075;cy+=(ty-cy)*.075;
                    aura.style.transform=`translate3d(${cx}px,${cy}px,0)`;
                    requestAnimationFrame(auraLoop);
                })();
            }

            /* Ambient shooting light */
            if(!reduce && innerWidth>768){
                const frag=document.createDocumentFragment();
                for(let i=0;i<6;i++){
                    const star=document.createElement('span');
                    star.className='v4-shooting-star';
                    star.style.left=`${Math.random()*65}%`;
                    star.style.top=`${Math.random()*45}%`;
                    star.style.setProperty('--star-dur',`${7+Math.random()*8}s`);
                    star.style.setProperty('--star-delay',`${-Math.random()*14}s`);
                    frag.appendChild(star);
                }
                document.body.appendChild(frag);
            }
        })();
    </script>

</body>

</html>