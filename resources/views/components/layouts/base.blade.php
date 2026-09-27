@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'type' => 'website',
    'lang' => 'en',
    'dir' => 'ltr',
])
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Theme Initialization (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Favicon & Official Brand Icons -->
    <link rel="icon" type="image/png" href="{{ asset('images/branding/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/branding/logo.png') }}">

    <x-seo 
        :title="$title" 
        :description="$description" 
        :keywords="$keywords" 
        :image="$image ?? asset('images/branding/logo.png')" 
        :type="$type" 
    />

    <!-- Google Fonts: Plus Jakarta Sans, Outfit, Inter & IBM Plex Sans Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 dark:bg-[#070F1E] dark:text-slate-100 min-h-screen flex flex-col selection:bg-[#D4AF37] selection:text-[#071A36] transition-colors duration-200">
    {{ $slot }}

    @stack('scripts')
</body>
</html>
