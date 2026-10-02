@php
    $customFavicon = $globalSettings['site_favicon'] ?? \App\Models\Setting::get('site_favicon');
    $faviconUrl = null;
    $faviconVersion = 1;

    if (!empty($customFavicon)) {
        if (str_starts_with($customFavicon, 'http://') || str_starts_with($customFavicon, 'https://')) {
            $faviconUrl = $customFavicon;
            $faviconVersion = time();
        } else {
            $cleanPath = ltrim($customFavicon, '/\\');
            if (str_starts_with($cleanPath, 'storage/')) {
                $relStoragePath = substr($cleanPath, 8);
            } else {
                $relStoragePath = $cleanPath;
            }

            $candidatePaths = [
                public_path('storage/' . $relStoragePath),
                storage_path('app/public/' . $relStoragePath),
                public_path($cleanPath),
            ];

            foreach ($candidatePaths as $fullPath) {
                if (file_exists($fullPath)) {
                    $faviconVersion = filemtime($fullPath);
                    break;
                }
            }

            if (str_starts_with($cleanPath, 'storage/') || str_starts_with($cleanPath, 'uploads/')) {
                $faviconUrl = asset($cleanPath);
            } else {
                $faviconUrl = asset('storage/' . $relStoragePath);
            }
        }
    }

    if (empty($faviconUrl)) {
        $faviconUrl = asset('favicon.ico');
        $defaultFavPath = public_path('favicon.ico');
        $faviconVersion = file_exists($defaultFavPath) ? filemtime($defaultFavPath) : 1;
    }

    $faviconExt = strtolower(pathinfo(parse_url($faviconUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
    $faviconType = match ($faviconExt) {
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'jpg', 'jpeg' => 'image/jpeg',
        'ico' => 'image/x-icon',
        default => null,
    };
@endphp
<link rel="icon" @if($faviconType) type="{{ $faviconType }}" @endif href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
<link rel="shortcut icon" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
<link rel="apple-touch-icon" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
