<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>
    @hasSection('title')
      @yield('title') - {{ $siteSettings['name'] }}
    @else
      {{ $siteSettings['title'] }} - {{ $siteSettings['name'] }}
    @endif
  </title>
  <meta name="description" content="@yield('meta_description', $siteSettings['description'])" />
  <meta name="keywords" content="{{ $siteSettings['keywords'] }}" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="{{ url()->current() }}" />
  <meta property="og:title" content="@yield('title', $siteSettings['title'])" />
  <meta property="og:description" content="{{$siteSettings['description']}}" />
  <meta property="og:image" content="{{$siteSettings['image_seo']}}" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="{{ $siteSettings['title'] }}" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="@yield('title', $siteSettings['title'])" />
  <meta name="twitter:description" content="{{$siteSettings['description']}}" />
  <meta name="twitter:image" content="{{$siteSettings['image_seo']}}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ $siteSettings['favicon'] }}">
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "{{ url('/') }}",
      "name": "@yield('title', $siteSettings['title'])",
      "description": "{{ $siteSettings['description'] }}",
      "publisher": {
        "@type": "Organization",
        "name": "{{ $siteSettings['title'] }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ $siteSettings['favicon'] }}"
        }
      }
    }
    </script>

  <!-- CSS -->
  <link rel="stylesheet" href="/assets/libs/simplebar/simplebar.css" />
  <link rel="stylesheet" href="/assets/libs/fontawesome-free-7.1.0-web/css/all.min.css" />
  <link rel="stylesheet" href="/assets/libs/feather-icon/feather.css" />
  <link rel="stylesheet" href="/assets/css/client/fonts/inter/inter.css" />
  <link rel="stylesheet" href="/assets/css/client/fonts/material/material.css" />
  <link rel="stylesheet" href="/assets/css/client/style.css" />
  <link rel="stylesheet" href="/assets/css/client/style-preset.css" />
  <link rel="stylesheet" href="/assets/libs/toastr/toastr.min.css" />
  <link rel="stylesheet" href="/assets/css/client/custom.css" />
  <!-- <link rel="stylesheet" href="/assets2/fonts/tabler-icons.min.css" /> -->
  <!-- <link rel="stylesheet" href="/assets/css/backend.css" /> -->

  <!-- Extra -->
  {!! $siteSettings['script_header'] !!}
  @yield('css')
</head>


<body data-pc-preset="preset-3" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="true"
  data-pc-theme="light">
  <div class="loader-bg">
    <div class="spinner"></div>
  </div>