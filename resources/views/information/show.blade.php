<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow">
    <title>{{ $title }} · University Connect</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;font-family:system-ui,-apple-system,Segoe UI,sans-serif;color:#f1f5f9;background:radial-gradient(ellipse at top left,#29275d 0,transparent 55%),radial-gradient(ellipse at bottom right,#392044 0,transparent 50%),#080d1c;display:flex;flex-direction:column}
        header{padding:26px clamp(20px,5vw,64px)}header a{color:#e2e8f0;text-decoration:none;font-weight:800;letter-spacing:.02em}header span{color:#a78bfa}main{flex:1;display:grid;place-items:center;padding:24px 20px 60px}.card{width:100%;max-width:680px;padding:clamp(28px,6vw,56px);border:1px solid #ffffff20;background:#0f172ae8;border-radius:28px;box-shadow:0 24px 70px #0004}.symbol{width:60px;height:60px;display:grid;place-items:center;background:linear-gradient(135deg,#7c3aed,#2563eb);border-radius:18px;font-size:30px;font-weight:800}.label{margin:26px 0 12px;color:#c4b5fd;font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase}h1{font-size:clamp(26px,5vw,38px);line-height:1.2;margin:0 0 20px}p{font-size:16px;line-height:1.8;color:#b8c4d8}.actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:30px}.button{display:inline-flex;justify-content:center;align-items:center;padding:13px 22px;border-radius:12px;text-decoration:none;font-weight:700;color:#fff;border:1px solid #ffffff25;background:#ffffff08}.primary{background:linear-gradient(110deg,#7c3aed,#2563eb);border-color:transparent}.note{margin-top:26px;font-size:13px;color:#8899b2}a:focus-visible{outline:3px solid #67e8f9;outline-offset:4px}@media(max-width:420px){.actions .button{width:100%}}
    </style>
</head>
<body>
    <header><a href="{{ route('home') }}">University <span>Connect</span></a></header>
    <main>
        <section class="card" aria-labelledby="page-title">
            <div class="symbol" aria-hidden="true">{{ $statusCode ?? 'i' }}</div>
            <div class="label">{{ isset($statusCode) ? 'Let us help you find your way' : 'University Account Information' }}</div>
            <h1 id="page-title">{{ $title }}</h1>
            <p>{{ $message }}</p>
            <nav class="actions" aria-label="Next steps">
                @auth
                    @if(!empty($destination))<a class="button primary" href="{{ route($destination[1]) }}">{{ $destination[0] }}</a>@endif
                    <a class="button {{ empty($destination) ? 'primary' : '' }}" href="{{ route('dashboard') }}">My Dashboard</a>
                @else
                    <a class="button primary" href="{{ route('login') }}">Login</a>
                @endauth
                <a class="button" href="{{ route('home') }}">Home</a>
            </nav>
            <p class="note">University Connect · Your university community</p>
        </section>
    </main>
</body>
</html>
