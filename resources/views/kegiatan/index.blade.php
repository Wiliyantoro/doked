@extends('layouts.main')

@section('title', 'Daftar Kegiatan')

@section('head')
    <link rel="stylesheet" href="path/to/your/custom.css">
    <style>
        /* Custom styles for this page */
        .center-image {
            display: flex;
            justify-content: center;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Kegiatan</h1>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createKegiatanModal">
            Tambah Kegiatan
        </button>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Rincian Kegiatan</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Foto</th>
                    <th>Dibuat oleh</th> <!-- Tambah kolom dibuat oleh -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp <!-- Inisialisasi variabel nomor urutan -->
                @foreach($kegiatans as $kegiatan)
                    <tr>
                        <td>{{ $no++ }}</td> <!-- Menampilkan nomor urutan -->
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td>{{ $kegiatan->rincian_kegiatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                        <td class="center-image">
                            @if($kegiatan->fotos->count() > 0)
                                @foreach($kegiatan->fotos as $foto)
                                    <img src="{{ url('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan" style="max-width: 100px;">
                                @endforeach
                            @else
                                Tidak ada foto
                            @endif
                        </td>
                        <td>{{ $kegiatan->user->name }}</td> <!-- Menampilkan nama pengguna yang membuat kegiatan -->
                        <td>
                            @if($kegiatan->user_id == auth()->id() || Auth::user()->level == 2)
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editKegiatanModal{{ $kegiatan->id }}">
                                    Edit
                                </button>
                            @endif
                            @if(Auth::user()->level == 1 || Auth::user()->level == 2)
                                <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                <a href="{{ route('kegiatan.print', $kegiatan->id) }}" class="btn btn-info btn-sm" target="_blank">
                                    Cetak
                                </a>
                            @endif
                        </td>
                    </tr>
                    @include('kegiatan.edit', ['kegiatan' => $kegiatan])
                @endforeach
            </tbody>
        </table>
    </div>

    @include('kegiatan.create')
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewImages(event, previewId) {
            var files = event.target.files;
            var output = document.getElementById(previewId);
            output.innerHTML = ''; // Clear the current content
            
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = (function(file) { // Create a closure to handle each file separately
                    return function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '300px';
                        img.style.marginTop = '10px';
                        output.appendChild(img);
                    };
                })(files[i]);
                reader.readAsDataURL(files[i]);
            }
        }

        // Menghilangkan alert setelah 5 detik
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>
@endsection
