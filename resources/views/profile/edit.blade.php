@extends('layouts.main')

@section('title', 'Edit Profil')

@section('content')
<div class="container">
    <h2>Edit Profil</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="form-width">
        @csrf

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
        </div>

        <div class="form-group">
            <label for="current_password">Kata Sandi Lama</label>
            <input type="password" name="current_password" id="current_password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi Baru</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <input type="checkbox" id="show_passwords" onclick="togglePasswords()"> Lihat Kata Sandi
        </div>

        <div class="form-group">
            <label for="foto_pengguna">Foto Pengguna</label>
            <input type="file" name="foto_pengguna" class="form-control" accept="image/*" onchange="previewImage(event)">

            <div id="imagePreview" class="mt-2">
                @if(Auth::user()->foto_pengguna)
                    <img src="{{ asset('storage/foto_pengguna/' . Auth::user()->foto_pengguna) }}" alt="Foto Pengguna" class="img-thumbnail" style="width: 150px;">
                @else
                    <p>Foto tidak ada</p>
                @endif
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        var imagePreview = document.getElementById('imagePreview');
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            // Remove existing content and add new image
            imagePreview.innerHTML = '';
            var img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail mt-2';
            img.style.width = '150px';
            imagePreview.appendChild(img);
        };

        reader.readAsDataURL(file);
    }

    function togglePasswords() {
        var passwordFields = ['current_password', 'password', 'password_confirmation'];
        passwordFields.forEach(function(fieldId) {
            var field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
        });
    }
</script>
@endpush

<style>
    .form-width {
        max-width: 600px;
        margin: auto;
    }
</style>
