@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')
<div class="container">
    <div class="text-center mt-5">
        <div class="rounded-circle overflow-hidden mx-auto" style="width: 150px; height: 150px;">
            @if(Auth::user()->foto_pengguna)
                <img src="{{ asset('storage/foto_pengguna/' . Auth::user()->foto_pengguna) }}" alt="Foto Pengguna" class="w-100 h-100">
            @else
                <p>Foto tidak ada</p>
            @endif
        </div>

        <h2 class="mt-4">{{ Auth::user()->name }}</h2>
        <p>{{ Auth::user()->email }}</p>
        <p>
            @if(Auth::user()->level == 1)
                Administrator
            @elseif(Auth::user()->level == 2)
                User
            @else
                Unknown
            @endif
        </p>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
    </div>
</div>
@endsection
