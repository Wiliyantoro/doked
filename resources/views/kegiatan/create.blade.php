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
    // Script to access the camera
    var video = document.getElementById('camera');
    var canvas = document.getElementById('canvas');
    var cameraPreview = document.getElementById('cameraPreview');

    // Check if getUserMedia is supported
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });
    } else {
        console.log("getUserMedia is not supported in this browser.");
    }

    function capturePhoto() {
        var context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert the canvas to a data URL and create an image element
        var dataURL = canvas.toDataURL('image/png');
        var img = document.createElement('img');
        img.src = dataURL;
        img.style.maxWidth = '100px';
        cameraPreview.appendChild(img);

        // Create a hidden input to hold the data URL
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'camera_photos[]';
        input.value = dataURL;
        cameraPreview.appendChild(input);
    }

    // Preview images function
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
                    img.style.maxWidth = '100px';
                    img.style.marginTop = '10px';
                    output.appendChild(img);
                };
            })(files[i]);
            reader.readAsDataURL(files[i]);
        }
    }
</script>
