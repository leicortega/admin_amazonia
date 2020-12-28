
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Amazonia C&L</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Dashboard de administrador Amazonia C&L." name="description" />
    <meta content="Leiner Ortega" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- datepicker -->
    <link href="{{ asset('assets/libs/air-datepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- SELECTIZE --}}
    <link href="{{ asset('assets/libs/selectize/css/selectize.css') }}" rel="stylesheet" type="text/css" />

    <!-- jvectormap -->
    <link href="{{ asset('assets/libs/jqvmap/jqvmap.min.css') }}" rel="stylesheet" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    @yield('MainCSS')

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
