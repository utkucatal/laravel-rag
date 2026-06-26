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

        </ul>
    </div>
</nav>
