<div class="modal fade" id="createKegiatanModal" tabindex="-1" aria-labelledby="createKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createKegiatanModalLabel">Tambah Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createKegiatanForm" action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kegiatan">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
                    </div>
                    <div class="form-group">
                        <label for="rincian_kegiatan">Rincian Kegiatan</label>
                        <textarea class="form-control" id="rincian_kegiatan" name="rincian_kegiatan" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
                        <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
                    </div>
                    <div class="form-group">
                        <label for="fotos">Upload Foto</label>
                        <input type="file" class="form-control-file" id="fotos" name="fotos[]" multiple accept="image/*" onchange="previewImages(event, 'previewContainer')">
                    </div>
                    <div class="form-group">
                        <label for="cameraInput">Ambil Foto dengan Kamera</label>
                        <div class="camera-container">
                            <video id="camera" class="w-100" autoplay></video>
                            <button type="button" class="btn btn-primary mt-2" onclick="capturePhoto()">Ambil Foto</button>
                            <button type="button" class="btn btn-secondary mt-2" id="switchCamera">Ganti Kamera</button>
                        </div>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div id="cameraPreview" class="d-flex flex-wrap"></div>
                    </div>
                    <div id="previewContainer" class="d-flex flex-wrap"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let currentStream;
    let video = document.getElementById('camera');
    let canvas = document.getElementById('canvas');
    let cameraPreview = document.getElementById('cameraPreview');
    let currentFacingMode = 'user'; // Default to front camera

    $(document).ready(function() {
        $('#createKegiatanModal').on('shown.bs.modal', function () {
            startCamera();
        });

        $('#createKegiatanModal').on('hidden.bs.modal', function () {
            stopCamera();
        });

        $('#switchCamera').on('click', function() {
            currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            startCamera();
        });
    });

    function startCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: currentFacingMode } })
            .then(stream => {
                currentStream = stream;
                video.srcObject = stream;
            })
            .catch(err => console.error('Error accessing camera: ', err));
    }

    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
    }

    function capturePhoto() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png');
        img.className = 'img-fluid';
        img.style.maxWidth = '100px';
        img.style.marginRight = '10px';
        cameraPreview.appendChild(img);

        // Simpan gambar ke dalam input file
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'capturedImages[]';
        input.value = img.src;
        document.getElementById('createKegiatanForm').appendChild(input);
    }

    function previewImages(event, previewContainerId) {
        const files = event.target.files;
        const previewContainer = document.getElementById(previewContainerId);
        previewContainer.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-fluid';
                img.style.maxWidth = '100px';
                img.style.marginRight = '10px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(files[i]);
        }
    }
</script>
