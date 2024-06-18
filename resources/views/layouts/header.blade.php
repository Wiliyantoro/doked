<header class="header bg-light">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>Header</h1>
        <div>
            <span>{{ Auth::user()->name }}</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="{{ route('logout') }}" class="btn btn-secondary btn-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </div>
    </div>
</header>
