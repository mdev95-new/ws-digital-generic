<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure digital product store — pay with crypto, no account needed, instant delivery">
    <title>@yield('title') — ws-digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #030712; font-family: 'Inter', system-ui, sans-serif; }
        .glow { box-shadow: 0 0 40px -12px rgba(16, 185, 129, 0.25); }
        .glow-lg { box-shadow: 0 0 60px -16px rgba(16, 185, 129, 0.2); }
        .card-hover { transition: all 0.25s ease; }
        .card-hover:hover { transform: translateY(-3px); }
        .gradient-border { position: relative; }
        .gradient-border::before {
            content: ''; position: absolute; inset: 0; border-radius: inherit;
            padding: 1px; background: linear-gradient(135deg, rgba(16,185,129,0.3), rgba(16,185,129,0.05), rgba(16,185,129,0.3));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
        }
        .hero-glow { position: relative; }
        .hero-glow::after {
            content: ''; position: absolute; top: -40%; left: 50%; translate: -50% 0;
            width: 600px; height: 400px; background: radial-gradient(ellipse, rgba(16,185,129,0.12), transparent 70%);
            pointer-events: none;
        }
        .tier-popular { border-color: rgba(16, 185, 129, 0.4); }
        input, select { transition: border-color 0.2s, box-shadow 0.2s; }
        input:focus, select:focus { border-color: #10b981 !important; box-shadow: 0 0 0 1px #10b981; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeIn 0.4s ease forwards; }
        .animate-in-d1 { animation: fadeIn 0.4s ease 0.1s forwards; opacity: 0; }
        .animate-in-d2 { animation: fadeIn 0.4s ease 0.2s forwards; opacity: 0; }
    </style>
</head>
<body class="text-gray-100 min-h-screen flex flex-col antialiased">
    <nav class="border-b border-gray-800/60 bg-gray-950/80 backdrop-blur-lg sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5 group">
                <span class="w-8 h-8 rounded-lg bg-brand-500/15 flex items-center justify-center group-hover:bg-brand-500/25 transition-colors">
                    <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <span class="text-lg font-bold">ws-<span class="text-brand-400">digital</span></span>
            </a>
            <div class="flex items-center gap-5 text-sm">
                <span class="text-gray-600 hidden sm:inline">🔗 Crypto</span>
                <span class="text-gray-700 hidden sm:inline">·</span>
                <span class="text-gray-600 hidden sm:inline">⚡ Instant</span>
                <span class="text-gray-700 hidden sm:inline">·</span>
                <span class="text-gray-600 hidden sm:inline">🔒 No account</span>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-6xl mx-auto px-5 pt-5 w-full animate-in">
            <div class="bg-brand-950/40 border border-brand-800/40 text-brand-200 p-4 rounded-xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-6xl mx-auto px-5 pt-5 w-full animate-in">
            <div class="bg-red-950/40 border border-red-800/40 text-red-200 p-4 rounded-xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="flex-1 max-w-6xl mx-auto px-5 py-12 w-full">
        @yield('content')
    </main>

    <footer class="border-t border-gray-800/50 py-8 mt-16">
        <div class="max-w-6xl mx-auto px-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
            <span>&copy; {{ date('Y') }} ws-digital</span>
            <div class="flex items-center gap-4">
                <span>BTC</span><span class="text-gray-700">·</span><span>Lightning</span><span class="text-gray-700">·</span><span>USDC</span><span class="text-gray-700">·</span><span>XMR</span>
            </div>
        </div>
    </footer>
</body>
</html>
