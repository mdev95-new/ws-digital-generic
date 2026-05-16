<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Digital product store">
    <title>@yield('title') - ws-digital-generic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen">
    <nav class="border-b border-gray-800 p-4">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-emerald-400">ws-digital</a>
            <div class="text-sm text-gray-400">secure · crypto · no accounts</div>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto p-4">
        @if(session('success'))
            <div class="bg-emerald-900/50 border border-emerald-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-900/50 border border-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>