<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title', $siteSettings['title'] ?? 'SUBOLD')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ $siteSettings['description'] ?? '' }}" />
    <meta name="keywords" content="{{ $siteSettings['keywords'] ?? '' }}" />
    <meta name="title" content="{{ $siteSettings['title'] ?? '' }}" />
    <meta property="og:image" content="{{ $siteSettings['image_seo'] ?? '' }}" />
    <link rel="shortcut icon" type="image/png" href="{{ $siteSettings['favicon'] ?? '/favicon.ico' }}" />

    <link href="/assets/libs/toastr/toastr.min.css" rel="stylesheet">
    <link href="/assets/libs/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/libs/fontawesome-free-7.1.0-web/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/client/auth.css">
    <link rel="stylesheet" href="/assets/css/client/custom.css" />

    @yield('css')

    {!! $siteSettings['script_header'] ?? '' !!}
</head>

<body>