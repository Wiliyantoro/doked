@extends('layouts.main')

@section('title', 'Edit Kegiatan')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Kegiatan</h1>

    @if(session('message'))
        <div class="alert alert-{{ session('message')['type'] }} alert-dismissible fade show" role="alert">
            {{ session('message')['text'] }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div id="new-photos-preview" class="mb-3">
        <h5>Preview Foto Baru</h5>
    
        @if (!empty($kegiatan->new_photos))
            @foreach($kegiatan->new_photos as $photo)
                <input type="hidden" name="new_photos[]" value="{{ $photo }}">
            @endforeach
        @endif
    </div>

    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required>
        </div>
        <div class="form-group">
            <label for="rincian_kegiatan">Rincian Kegiatan</label>
            <textarea class="form-control" id="rincian_kegiatan" name="rincian_kegiatan" rows="3" required>{{ $kegiatan->rincian_kegiatan }}</textarea>
        </div>
        <div class="form-group">
            <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
            <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ \Illuminate\Support\Carbon::parse($kegiatan->tanggal_kegiatan)->format('Y-m-d') }}" required>
        </div>
        
        <div class="form-group">
            <label for="fotos">Foto</label>
            <input type="file" class="form-control-file" id="fotos" name="fotos[]" multiple onchange="previewSelectedPhotos(event)">
            <div class="mt-3">
                @foreach($kegiatan->fotos as $foto)
                    <div id="foto-{{ $foto->id }}" class="foto-wrapper mb-3 d-flex align-items-center">
                        <img src="{{ url('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan" style="max-width: 100px;">
                        <div class="ml-3">
                            <button type="button" onclick="changeFoto({{ $foto->id }})" class="btn btn-info btn-sm">Ganti Foto</button>
                            <button type="button" onclick="ambilFotoDariKamera({{ $foto->id }})" class="btn btn-primary btn-sm">Ambil Foto dari Kamera</button>
                            <button type="button" onclick="deleteFoto({{ $foto->id }})" class="btn btn-danger btn-sm">Hapus Foto</button>
                            <input type="file" class="form-control-file d-none" id="input-foto-{{ $foto->id }}" name="replaced_fotos[{{ $foto->id }}]" onchange="previewFoto({{ $foto->id }})">
                            <input type="hidden" id="camera-photo-{{ $foto->id }}" name="camera_photos[{{ $foto->id }}]">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<!-- Modal Ambil Foto dari Kamera -->
<div class="modal fade" id="ambilFotoModal" tabindex="-1" aria-labelledby="ambilFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ambilFotoModalLabel">Ambil Foto dari Kamera</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tempatkan elemen video kamera di sini -->
                <video id="video" width="100%" height="auto" autoplay></video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="ambilFoto()">Ambil Foto</button>
                <button type="button" class="btn btn-info" onclick="gantiKamera()">Ganti Kamera</button>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
    let videoStream = null;
    let currentFacingMode = 'environment'; // 'user' for front camera, 'environment' for back camera
    let currentFotoId = null; // Variable untuk menyimpan fotoId saat ini

    // Fungsi untuk memulai video dari kamera
    function startCamera() {
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: currentFacingMode
                }
            })
            .then(function(stream) {
                videoStream = stream;
                var video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            })
            .catch(function(error) {
                console.error('Error accessing the camera: ', error);
            });
    }

    // Fungsi untuk mengambil foto dari kamera untuk foto tertentu
    function ambilFotoDariKamera(fotoId) {
        $('#input-foto-' + fotoId).val(''); // Reset nilai input file
        $('#ambilFotoModal').modal('show'); // Memunculkan modal ambil foto
        currentFotoId = fotoId; // Simpan fotoId saat ini ke dalam variabel
    }

    // Fungsi untuk mengambil foto
    function ambilFoto() {
        var video = document.getElementById('video');
        var canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        var context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Konversi gambar ke base64 untuk ditampilkan di preview atau dikirim ke server
        var base64Image = canvas.toDataURL('image/jpeg');

        if (currentFotoId) {
            // Ganti foto yang sudah ada dengan foto baru
            var img = document.querySelector('#foto-' + currentFotoId + ' img');
            img.src = base64Image;

            // Sisipkan base64Image ke dalam input hidden yang bersangkutan
            var inputFoto = document.getElementById('camera-photo-' + currentFotoId);
            inputFoto.value = base64Image;
        } else {
            // Tampilkan gambar di preview untuk inputan baru
            var img = document.createElement('img');
            img.src = base64Image;
            img.style.maxWidth = '100px'; // Sesuaikan ukuran sesuai kebutuhan
            var previewContainer = document.getElementById('new-photos-preview');
            previewContainer.appendChild(img);

            // Sisipkan base64Image ke dalam input hidden yang bersangkutan
            var inputFoto = document.createElement('input');
            inputFoto.type = 'hidden';
            inputFoto.name = 'camera_photos[]';
            inputFoto.value = base64Image;
            previewContainer.appendChild(inputFoto);
        }

        // Tutup modal setelah ambil foto
        $('#ambilFotoModal').modal('hide');

        // Hentikan stream kamera
        stopCamera();
    }

    // Fungsi untuk mengganti kamera (depan/belakang)
    function gantiKamera() {
        currentFacingMode = (currentFacingMode === 'environment') ? 'user' : 'environment'; // Toggle mode kamera
        stopCamera();
        startCamera();
    }

    // Fungsi untuk menghentikan video stream kamera
    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(function(track) {
                track.stop();
            });
        }
    }

    // Panggil fungsi untuk memulai kamera saat modal ditampilkan
    $('#ambilFotoModal').on('shown.bs.modal', function() {
        startCamera();
    });

    // Panggil fungsi untuk menghentikan kamera saat modal ditutup
    $('#ambilFotoModal').on('hidden.bs.modal', function() {
        stopCamera();
    });

    // Fungsi untuk menampilkan preview foto yang dipilih
    function previewSelectedPhotos(event) {
        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px'; // Sesuaikan ukuran sesuai kebutuhan
                var previewContainer = document.getElementById('new-photos-preview');
                previewContainer.appendChild(img);
            }

            reader.readAsDataURL(file);
        }
    }

    // Fungsi untuk mengubah foto yang sudah ada
    function changeFoto(fotoId) {
        document.getElementById('input-foto-' + fotoId).click();
    }

    // Fungsi untuk menampilkan preview foto yang akan diubah
    function previewFoto(fotoId) {
        var input = document.getElementById('input-foto-' + fotoId);
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.querySelector('#foto-' + fotoId + ' img');
            img.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }

    // Fungsi untuk menghapus foto
    function deleteFoto(fotoId) {
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/kegiatan/foto/' + fotoId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                var fotoElement = document.getElementById('foto-' + fotoId);
                fotoElement.parentNode.removeChild(fotoElement);
                showAlert('success', 'Foto berhasil dihapus.');
            } else {
                showAlert('danger', 'Gagal menghapus foto.');
            }
        })
        .catch(error => {
            showAlert('danger', 'Terjadi kesalahan: ' + error.message);
        });
    }

    // Fungsi untuk menampilkan alert
    function showAlert(type, message) {
        var alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', 'alert-' + type, 'alert-dismissible', 'fade', 'show');
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

        var container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);

        setTimeout(function() {
            $(alertDiv).alert('close');
        }, 3000);
    }
</script>
@endpush
