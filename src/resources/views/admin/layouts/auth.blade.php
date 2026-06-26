<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('template/static/img/icons/icon-48x48.png') }}"/>
    <title>@yield('title', 'AdminKit')</title>
    <link href="{{ asset('template/static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<main class="d-flex w-100">
    @yield('content')
</main>

<script src="{{ asset('template/static/js/app.js') }}"></script>
</body>

</html>
