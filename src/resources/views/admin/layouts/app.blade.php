<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('template/static/img/icons/icon-48x48.png') }}"/>

    <title>@yield('title', 'Admin')</title>

    <link href="{{ asset('template/static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<div class="wrapper">
    @include('admin.partials.sidebar')

    <div class="main">
        @include('admin.partials.navbar')

        <main class="content">
            <div class="container-fluid p-0">
                @yield('content')
            </div>
        </main>

        @include('admin.partials.footer')
    </div>
</div>

<script src="{{ asset('template/static/js/app.js') }}"></script>
</body>

</html>
