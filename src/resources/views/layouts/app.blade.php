<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure digital product store — pay with crypto, no account needed, instant delivery">
    <title>@yield('title') — ws-digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #030712; }
        .glow { box-shadow: 0 0 40px -12px rgba(16, 185, 129, 0.3); }
        .card-hover { transition: all 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -8px rgba(16, 185, 129, 0.15); }
        .gradient-border { position: relative; }
        .gradient-border::before {
            content: ''; position: absolute; inset: 0; border-radius: inherit;
            padding: 1px; background: linear-gradient(135deg, rgba(16,185,129,0.3), rgba(16,185,129,0.05), rgba(16,185,129,0.3));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
        }
    </style>
</head>
<body class="text-gray-100 min-h-screen flex flex-col">
    <nav class="border-b border-gray-800/60 bg-gray-950/80 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <span class="text-xl font-bold text-white">ws-<span class="text-brand-400">digital</span></span>
            </a>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-500 hidden sm:inline">🔗 Crypto payments</span>
                <span class="text-gray-600 hidden sm:inline">·</span>
                <span class="text-gray-500 hidden sm:inline">⚡ Instant delivery</span>
                <span class="text-gray-600 hidden sm:inline">·</span>
                <span class="text-gray-500 hidden sm:inline">🔒 No account</span>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 pt-4 w-full">
            <div class="bg-brand-900/30 border border-brand-700/50 text-brand-200 p-4 rounded-xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-6xl mx-auto px-4 pt-4 w-full">
            <div class="bg-red-900/30 border border-red-700/50 text-red-200 p-4 rounded-xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="flex-1 max-w-6xl mx-auto px-4 py-8 w-full">
        @yield('content')
    </main>

    <footer class="border-t border-gray-800/60 py-6 mt-12">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
            <span>&copy; {{ date('Y') }} ws-digital — secure crypto commerce</span>
            <span>BTC · Lightning · USDC · XMR</span>
        </div>
    </footer>
</body>
</html>
