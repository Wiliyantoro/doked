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

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
        </div>

        <div class="form-group">
            <label for="foto_pengguna">Foto Pengguna</label>
            <input type="file" name="foto_pengguna" class="form-control" accept="image/*" onchange="previewImage(event)">
            
            <div id="imagePreview" class="mt-2">
                @if(Auth::user()->foto_pengguna)
                    <img src="{{ Storage::url(Auth::user()->foto_pengguna) }}" alt="Foto Pengguna" class="img-thumbnail" style="width: 150px;">
                @else
                    <p>Foto tidak ada</p>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi Baru</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
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
            imagePreview.innerHTML = '<img src="' + e.target.result + '" alt="Foto Pengguna" class="img-thumbnail mt-2" style="width: 150px;">';
        };

        reader.readAsDataURL(file);
    }
</script>
@endpush
