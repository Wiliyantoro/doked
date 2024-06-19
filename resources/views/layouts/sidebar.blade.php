<!-- Update Sidebar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark sidebar">
    <div class="container-fluid flex-column">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ Request::routeIs('kegiatan.index') ? 'active' : '' }}" href="{{ route('kegiatan.index') }}">
                        <i class="fas fa-calendar-alt"></i> Kegiatan
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ Request::routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user"></i> Profil
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ Request::routeIs('settings') ? 'active' : '' }}" href="#">
                        <i class="fas fa-cogs"></i> Setting
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
