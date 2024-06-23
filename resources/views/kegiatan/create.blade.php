<div class="modal fade" id="createKegiatanModal" tabindex="-1" aria-labelledby="createKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createKegiatanModalLabel">Tambah Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data">
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
                            <video id="camera" width="100%" autoplay></video>
                            <button type="button" class="btn btn-primary mt-2" onclick="capturePhoto()">Ambil Foto</button>
                            <button type="button" class="btn btn-secondary mt-2" id="switchCamera">Ganti Kamera</button>
                        </div>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div id="cameraPreview"></div>
                    </div>
                    <div id="previewContainer"></div>
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

        const constraints = {
            video: {
                facingMode: currentFacingMode
            }
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(stream => {
                currentStream = stream;
                video.srcObject = stream;
                video.play();
            })
            .catch(err => {
                console.log("An error occurred: " + err);
            });
    }

    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
        video.srcObject = null;
        document.getElementById('cameraPreview').innerHTML = "";
    }

    function capturePhoto() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataURL = canvas.toDataURL('image/png');
        const img = document.createElement('img');
        img.src = dataURL;
        img.style.maxWidth = '100px';
        cameraPreview.appendChild(img);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'camera_photos[]';
        input.value = dataURL;
        cameraPreview.appendChild(input);
    }

    function previewImages(event, previewContainerId) {
        const previewContainer = document.getElementById(previewContainerId);
        previewContainer.innerHTML = '';

        const files = event.target.files;
        for (const file of files) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }
</script>
