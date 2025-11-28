<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Title -->
    <title>@yield('title', $siteSettings['title'])</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $siteSettings['favicon'] }}">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="/assets/libs/fontawesome-free-7.1.0-web/css/all.min.css" />
    <link rel="stylesheet" href="/assets/css/admin/style.min.css">
    <!-- <link rel="stylesheet" href="/assets/css/admin/style-orange.min.css"> -->
    <link rel="stylesheet" href="/assets/libs/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/libs/toastr/toastr.min.css">

    @yield('css')

    <!-- <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }
    </style> -->
</head>

<body>
    <!-- Loader -->
    <div class="preloader">
        <img src="{{ $siteSettings['favicon'] }}" alt="loader" class="lds-ripple img-fluid" />
    </div>

    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">