@extends('layouts.main')

@section('title', 'Daftar Kegiatan')

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
                    <th>Dibuat oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($kegiatans as $kegiatan)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td>{{ $kegiatan->rincian_kegiatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                        <td class="center-image">
                            <div class="foto-wrapper">
                                @foreach($kegiatan->fotos as $foto)
                                    <a href="{{ url('storage/' . $foto->nama_file) }}" data-lightbox="kegiatan{{ $kegiatan->id }}">
                                        <img src="{{ url('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan" style="max-width: 100px;">
                                    </a>
                                @endforeach
                            </div>
                        </td>
                        <td>{{ $kegiatan->user->name }}</td>
                        <td>
                            @if($kegiatan->user_id == auth()->id() || Auth::user()->level == 2)
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editKegiatanModal{{ $kegiatan->id }}">
                                    Edit
                                </button>
                            @endif
                            @if(Auth::user()->level == 1 || Auth::user()->level == 2)
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $kegiatan->id }}">
                                    Hapus
                                </button>
                                <form id="deleteForm{{ $kegiatan->id }}" action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
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
    @include('kegiatan.confdel') {{-- modal untuk konfirmasi hapus --}} 
@endsection

@push('styles')
    <!-- CSS Lightbox2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
@endpush

@push('scripts')
    <!-- JavaScript Lightbox2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            // Initialize Lightbox2
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'alwaysShowNavOnTouchDevices': true,
                'disableScrolling': true // Menonaktifkan pengguliran ketika lightbox terbuka
            });
    
            // Menambahkan tombol unduh dan cetak
            $('.lightbox').append('<div class="button-container">' +
                '<button class="lightbox-download btn btn-primary mr-2">Unduh</button>' +
                '<button class="lightbox-print btn btn-secondary" onclick="printImage()">Cetak</button>' +
                '</div>');
    
            // Menangani klik tombol unduh
            $(document).on('click', '.lightbox-download', function(e){
                e.preventDefault();
                var imageUrl = $('.lb-image').attr('src');
                window.location.href = imageUrl;
            });
        });
    
        // Fungsi cetak
        function printImage() {
            var imageUrl = $('.lb-image').attr('src');
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Cetak Gambar</title></head><body><img src="' + imageUrl + '" style="max-width:100%;"></body></html>');
            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
    
    
@endpush
