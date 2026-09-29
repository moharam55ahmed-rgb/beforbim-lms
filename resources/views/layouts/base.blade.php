@php
    $currentLocale = app()->getLocale();
    $isRtl = $currentLocale === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}" class="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Beforbim') }} — Building Information Modeling & Digital Engineering Academy</title>

    <!-- Google Fonts: Outfit, Plus Jakarta Sans, Inter, JetBrains Mono & Tajawal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @stack('styles')
</head>
<body class="{{ $isRtl ? 'font-[\'Tajawal\',sans-serif]' : 'font-sans' }} antialiased text-slate-800 bg-[#F5F7FA] min-h-screen flex flex-col selection:bg-[#D4AF37] selection:text-[#071A36]">
    {{ $slot }}

    @stack('scripts')
</body>
</html>
