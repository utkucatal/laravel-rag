<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="template/static/img/icons/icon-48x48.png"/>

    <link rel="canonical" href="https://demo-basic.adminkit.io/"/>

    <title>Home</title>

    <link href="{{ asset('template/static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="{{ url('/') }}">
                <span class="align-middle">Alloui</span>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Home
                </li>

                <li class="sidebar-item {{ request()->routeIs('home.index') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('home.index') }}">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Index</span>
                    </a>
                </li>

                <li class="sidebar-header">
                    Admin
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.index') }}">
                        <i class="align-middle" data-feather="book"></i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-header">
                    Rag
                </li>

                <li class="sidebar-item ">
                    <a class="sidebar-link" href="{{ route('rag') }}">
                        <i class="align-middle" data-feather="airplay"></i> <span class="align-middle">Rag Search</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">

        <nav class="navbar navbar-expand navbar-light navbar-bg" style="height: 60px;">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>
            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-link d-none d-sm-inline-block" href="{{ route('admin.login') }}">
                            <span class="text-dark">Index</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="content">
            <div class="container-fluid p-0">

                    <div class="col-12 d-flex">
                        <div class="card flex-fill w-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0"></h5>
                            </div>
                            <div class="card-body d-flex w-100" style="height: 400px;">
                            </div>
                        </div>
                    </div>

            </div>
        </main>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-start">
                        <p class="mb-0">
                            <strong>Utku</a> - Bootstrap Template</strong></a> &copy;
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('template/static/js/app.js')}}"></script>
</body>

</html>
