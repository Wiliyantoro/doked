<header class="header bg-light">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        {{-- Pindahkan foto profil dan nama akun ke paling kanan --}}
        <div class="d-flex align-items-center ml-auto">
            {{-- Tampilkan foto profil dalam bentuk bulat --}}
            <div class="rounded-circle overflow-hidden ml-2 mr-2" style="width: 40px; height: 40px;">
                <img src="{{ asset('storage/foto_pengguna/' . Auth::user()->foto_pengguna) }}" alt="Profile Picture" class="w-100">
            </div>
            {{-- Tampilkan nama akun yang sedang login --}}
            <span>{{ Auth::user()->name }}</span>
        </div>

        {{-- Tombol dropdown menu dan logout berada di sebelah kiri --}}
        <div class="d-flex align-items-center">
            {{-- Tombol dropdown menu --}}
            <div class="dropdown ml-2">
                <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Profil
                </a>
                <div class="dropdown-menu" aria-labelledby="userDropdown">
                    {{-- Isi dropdown menu --}}
                    <a class="dropdown-item" href="{{ route('profile.index') }}">Profil Saya</a>
                    <a class="dropdown-item" href="#">Pengaturan</a>
                    <div class="dropdown-divider"></div>
                    {{-- Tambahkan tombol logout di dalam dropdown menu --}}
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>
            {{-- Tombol logout di luar dropdown menu --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="ml-2">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
            </form>
        </div>
    </div>
</header>
