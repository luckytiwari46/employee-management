<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','App')</title>

  {{-- Vite-built local CSS --}}
  @vite(['resources/css/app.css'])

  <style>
    /* user dashbord  */
       body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link, .dropdown-toggle {
            color: #fff !important;
        }
        .welcome-card {
            margin-top: 100px;
        }
        /* end userdashbord s */
    </style>
</head>
<body>
  @yield('content')


