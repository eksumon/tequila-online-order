<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle ?? 'TequilaPOS' }}</title>
    <meta name="description" content="{{ $pageDescription ?? '' }}" />
    <meta name="author" content="TequilaPOS" />
    <link rel="icon" href="/favicon.ico" />

    <meta property="og:type"        content="website" />
    <meta property="og:title"       content="{{ $pageTitle ?? 'TequilaPOS' }}" />
    <meta property="og:description" content="{{ $pageDescription ?? '' }}" />
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:title"       content="{{ $pageTitle ?? 'TequilaPOS' }}" />
    <meta name="twitter:description" content="{{ $pageDescription ?? '' }}" />

    <link rel="canonical" href="{{ url()->current() }}" />

    <link rel="stylesheet" crossorigin href="/assets/index-CUN12bqC.css" />
    <script type="module" crossorigin src="/assets/index-q4IBxL5v.js"></script>
</head>
<body>
    <div id="root"></div>
    @yield('content')
</body>
</html>
