@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
])

@php
    $cms = app(\App\Modules\Setting\Services\CmsSettingService::class);
    $siteName = $cms->get('site_name_ar', config('app.name', 'Beforbim'));
    $pageTitle = $title ? "{$title} — {$siteName}" : $cms->get('seo_meta_title', "{$siteName} — منصة هندسة نمذجة معلومات البناء وتطوير الكفاءات");
    $metaDescription = $description ?? $cms->get('seo_meta_description', 'أكاديمية Beforbim الرائدة في برامج دبلومات BIM المعتمدة، هندسة التشييد الرقمي، وتطبيقات Revit, Navisworks, Civil 3D, و Dynamo مع نخبة من الاستشاريين الدوليين.');
    $metaKeywords = $keywords ?? $cms->get('seo_meta_keywords', 'BIM, Revit, Navisworks, Civil 3D, نمذجة معلومات البناء, هندسة مدنية, هندسة معمارية, دورات هندسية, السعودية, مصر');
    $ogImage = $image ?? asset('images/beforbim-og.jpg');
    $currentUrl = $url ?? url()->current();
@endphp

<!-- Primary Meta Tags -->
<title>{{ $pageTitle }}</title>
<meta name="title" content="{{ $pageTitle }}">
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="author" content="Beforbim Engineering Academy">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $currentUrl }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="ar_SA">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $currentUrl }}">
<meta property="twitter:title" content="{{ $pageTitle }}">
<meta property="twitter:description" content="{{ $metaDescription }}">
<meta property="twitter:image" content="{{ $ogImage }}">
