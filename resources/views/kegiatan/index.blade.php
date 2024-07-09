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
                            @foreach($kegiatan->fotos->take(4) as $foto)
                                <img src="{{ url('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan" class="photo-thumbnail" data-photos="{{ json_encode($kegiatan->fotos->pluck('nama_file')) }}" data-index="{{ $loop->index }}" style="max-width: 100px; cursor: pointer;">
                            @endforeach
                            @if($kegiatan->fotos->count() > 4)
                                <div class="lihat-semua">
                                    <button class="btn btn-link lihat-semua-btn" data-id="{{ $kegiatan->id }}">Lihat Semua</button>
                                </div>
                            @endif
                        </div>
                        <div class="foto-lengkap" id="foto-lengkap-{{ $kegiatan->id }}" style="display: none;">
                            @foreach($kegiatan->fotos as $foto)
                                <img src="{{ url('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan" style="max-width: 100px;">
                            @endforeach
                            <div class="lihat-semua">
                                <button class="btn btn-link sembunyikan-semua-btn" data-id="{{ $kegiatan->id }}">Sembunyikan</button>
                            </div>
                        </div>
                    </td>
                    <td>{{ $kegiatan->user->name }}</td>
                    <td>
                        @if($kegiatan->user_id == auth()->id() || Auth::user()->level == 2)
                            <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>
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
            @endforeach
        </tbody>
    </table>
</div>

@include('kegiatan.create')
@include('kegiatan.confdel') {{-- modal untuk konfirmasi hapus --}}
@include('kegiatan.photo_modal') {{-- modal untuk menampilkan foto --}}
@endsection

@push('styles')
<style>
    .center-image {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .camera-container {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }

    .camera-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .foto-wrapper, .foto-lengkap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .lihat-semua {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-top: 10px;
    }
    .camera-container button {
    position: relative;
    z-index: 999; /* Atur nilai z-index agar lebih tinggi dari elemen video */
    }
    .modal-content-1 {
    background-color: rgba(255, 255, 255, 0.8); /* Warna putih dengan opacity 80% */
    border: 1px solid #e0e0e0; /* Garis tepi modal */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Bayangan di sekitar modal */
    }
</style>
@endpush
@push('styles')
<style>
    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        height: 500px; /* Fixed height for the container */
        width: 100%;
        position: relative;
    }

    .image-container img {
        transition: transform 0.3s ease, width 0.3s ease, height 0.3s ease;
        max-width: 100%;
        max-height: 100%;
    }
</style>
@endpush
@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('.photo-thumbnail').click(function() {
        var photos = JSON.parse($(this).attr('data-photos'));
        var index = $(this).attr('data-index');
        var rotation = 0;

        $('#modalImage').attr('src', '/storage/' + photos[index]);
        $('#downloadPhoto').attr('href', '/storage/' + photos[index]);
        $('#modalImage').css('transform', 'rotate(0deg)');
        $('#photoModal').modal('show');
        adjustImageSize(rotation);

        $('#prevPhoto').off().click(function() {
            index = (index + photos.length - 1) % photos.length;
            $('#modalImage').attr('src', '/storage/' + photos[index]);
            $('#downloadPhoto').attr('href', '/storage/' + photos[index]);
            rotation = 0;
            $('#modalImage').css('transform', 'rotate(0deg)');
            adjustImageSize(rotation);
        });

        $('#nextPhoto').off().click(function() {
            index = (index + 1) % photos.length;
            $('#modalImage').attr('src', '/storage/' + photos[index]);
            $('#downloadPhoto').attr('href', '/storage/' + photos[index]);
            rotation = 0;
            $('#modalImage').css('transform', 'rotate(0deg)');
            adjustImageSize(rotation);
        });

        $('#rotateLeft').off().click(function() {
            rotation -= 90;
            $('#modalImage').css('transform', 'rotate(' + rotation + 'deg)');
            adjustImageSize(rotation);
        });

        $('#rotateRight').off().click(function() {
            rotation += 90;
            $('#modalImage').css('transform', 'rotate(' + rotation + 'deg)');
            adjustImageSize(rotation);
        });

        $('#printPhoto').off().click(function() {
            var printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write('<html><body style="text-align:center;"><img src="/storage/' + photos[index] + '" style="max-width:100%; transform: rotate(' + rotation + 'deg);"></body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });

        function adjustImageSize(rotation) {
            var img = $('#modalImage');
            var container = $('.image-container');
            var containerWidth = container.width();
            var containerHeight = container.height();
            var imgWidth = img[0].naturalWidth;
            var imgHeight = img[0].naturalHeight;
            var imgAspect = imgWidth / imgHeight;
            var containerAspect = containerWidth / containerHeight;

            if (rotation % 180 !== 0) {
                imgAspect = 1 / imgAspect;
            }

            if (imgAspect > containerAspect) {
                img.css({
                    'width': rotation % 180 === 0 ? 'auto' : '100%',
                    'height': rotation % 180 === 0 ? '100%' : 'auto'
                });
            } else {
                img.css({
                    'width': rotation % 180 === 0 ? '100%' : 'auto',
                    'height': rotation % 180 === 0 ? 'auto' : '100%'
                });
            }
        }
    });
});

</script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    function previewImages(event, previewId) {
        var files = event.target.files;
        var output = document.getElementById(previewId);
        output.innerHTML = '';

        for (var i = 0; i < files.length; i++) {
            var reader = new FileReader();
            reader.onload = (function(file) {
                return function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.marginRight = '10px';
                    output.appendChild(img);
                };
            })(files[i]);
            reader.readAsDataURL(files[i]);
        }
    }

    // Show all photos
    $(document).on('click', '.lihat-semua-btn', function() {
        var id = $(this).data('id');
        $('#foto-lengkap-' + id).show();
        $(this).closest('.foto-wrapper').hide();
    });

    // Hide all photos
    $(document).on('click', '.sembunyikan-semua-btn', function() {
        var id = $(this).data('id');
        $('#foto-lengkap-' + id).hide();
        $(this).closest('.foto-lengkap').hide();
        $('.foto-wrapper[data-id="' + id + '"]').show();
    });
</script>
@endpush
