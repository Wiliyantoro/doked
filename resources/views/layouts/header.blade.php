<header class="header bg-light">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        {{-- Breadcrumbs --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @yield('breadcrumbs')
            </ol>
        </nav>

        {{-- User Profile Info --}}
        <div class="d-flex align-items-center ml-auto">
            {{-- User Name --}}
            <span>{{ Auth::user()->name }}</span>
            
            {{-- User Profile Picture --}}
            <div class="rounded-circle overflow-hidden ml-2 mr-2" style="width: 40px; height: 40px;">
                <img src="{{ asset('storage/foto_pengguna/' . Auth::user()->foto_pengguna) }}" alt="Profile Picture" class="w-100">
            </div>

            {{-- User Profile Dropdown Menu --}}
            <div class="dropdown ml-2">
                <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Profil
                </a>
                <div class="dropdown-menu" aria-labelledby="userDropdown">
                    {{-- Dropdown Menu Items --}}
                    <a class="dropdown-item" href="{{ route('profile.index') }}">Profil Saya</a>
                    <a class="dropdown-item" href="{{ route('settings.index') }}">Pengaturan</a>
                    <div class="dropdown-divider"></div>
                    {{-- Logout Button Inside Dropdown --}}
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>
            {{-- Logout Button Outside Dropdown --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="ml-2 d-none d-md-inline">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
            </form>
        </div>
    </div>
</header>
