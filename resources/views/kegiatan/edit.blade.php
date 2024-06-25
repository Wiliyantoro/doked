<div class="modal fade" id="editKegiatanModal{{ $kegiatan->id }}" tabindex="-1" aria-labelledby="editKegiatanModalLabel{{ $kegiatan->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKegiatanModalLabel{{ $kegiatan->id }}">Edit Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editKegiatanForm{{ $kegiatan->id }}" action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kegiatan{{ $kegiatan->id }}">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama_kegiatan{{ $kegiatan->id }}" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rincian_kegiatan{{ $kegiatan->id }}">Rincian Kegiatan</label>
                        <textarea class="form-control" id="rincian_kegiatan{{ $kegiatan->id }}" name="rincian_kegiatan" required>{{ $kegiatan->rincian_kegiatan }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kegiatan{{ $kegiatan->id }}">Tanggal Kegiatan</label>
                        <input type="date" class="form-control" id="tanggal_kegiatan{{ $kegiatan->id }}" name="tanggal_kegiatan" value="{{ $kegiatan->tanggal_kegiatan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="fotos{{ $kegiatan->id }}">Upload Foto</label>
                        <input type="file" class="form-control-file" id="fotos{{ $kegiatan->id }}" name="fotos[]" multiple accept="image/*" onchange="previewImages(event, 'previewContainer{{ $kegiatan->id }}')">
                    </div>
                    <div class="form-group">
                        <label for="cameraInput{{ $kegiatan->id }}">Ambil Foto dengan Kamera</label>
                        <div class="camera-container">
                            <video id="camera{{ $kegiatan->id }}" class="w-100" autoplay></video>
                            <button type="button" class="btn btn-primary mt-2" onclick="capturePhoto{{ $kegiatan->id }}()">Ambil Foto</button>
                            <button type="button" class="btn btn-secondary mt-2" id="switchCamera{{ $kegiatan->id }}">Ganti Kamera</button>
                        </div>
                        <canvas id="canvas{{ $kegiatan->id }}" style="display: none;"></canvas>
                        <div id="cameraPreview{{ $kegiatan->id }}" class="d-flex flex-wrap"></div>
                    </div>
                    <div id="previewContainer{{ $kegiatan->id }}" class="d-flex flex-wrap"></div>
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
    let currentStream{{ $kegiatan->id }};
    let video{{ $kegiatan->id }} = document.getElementById('camera{{ $kegiatan->id }}');
    let canvas{{ $kegiatan->id }} = document.getElementById('canvas{{ $kegiatan->id }}');
    let cameraPreview{{ $kegiatan->id }} = document.getElementById('cameraPreview{{ $kegiatan->id }}');
    let currentFacingMode{{ $kegiatan->id }} = 'user'; // Default to front camera

    $(document).ready(function() {
        $('#editKegiatanModal{{ $kegiatan->id }}').on('shown.bs.modal', function () {
            startCamera{{ $kegiatan->id }}();
        });

        $('#editKegiatanModal{{ $kegiatan->id }}').on('hidden.bs.modal', function () {
            stopCamera{{ $kegiatan->id }}();
        });

        $('#switchCamera{{ $kegiatan->id }}').on('click', function() {
            currentFacingMode{{ $kegiatan->id }} = currentFacingMode{{ $kegiatan->id }} === 'user' ? 'environment' : 'user';
            startCamera{{ $kegiatan->id }}();
        });
    });

    function startCamera{{ $kegiatan->id }}() {
        if (currentStream{{ $kegiatan->id }}) {
            currentStream{{ $kegiatan->id }}.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: currentFacingMode{{ $kegiatan->id }} } })
            .then(stream => {
                currentStream{{ $kegiatan->id }} = stream;
                video{{ $kegiatan->id }}.srcObject = stream;
            })
            .catch(err => console.error('Error accessing camera: ', err));
    }

    function stopCamera{{ $kegiatan->id }}() {
        if (currentStream{{ $kegiatan->id }}) {
            currentStream{{ $kegiatan->id }}.getTracks().forEach(track => track.stop());
        }
    }

    function capturePhoto{{ $kegiatan->id }}() {
        const context = canvas{{ $kegiatan->id }}.getContext('2d');
        canvas{{ $kegiatan->id }}.width = video{{ $kegiatan->id }}.videoWidth;
        canvas{{ $kegiatan->id }}.height = video{{ $kegiatan->id }}.videoHeight;
        context.drawImage(video{{ $kegiatan->id }}, 0, 0, canvas{{ $kegiatan->id }}.width, canvas{{ $kegiatan->id }}.height);

        const img = document.createElement('img');
        img.src = canvas{{ $kegiatan->id }}.toDataURL('image/png');
        img.className = 'img-fluid';
        img.style.maxWidth = '100px';
        img.style.marginRight = '10px';
        cameraPreview{{ $kegiatan->id }}.appendChild(img);

        // Simpan gambar ke dalam input file
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'capturedImages[]';
        input.value = img.src;
        document.getElementById('editKegiatanForm{{ $kegiatan->id }}').appendChild(input);
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
