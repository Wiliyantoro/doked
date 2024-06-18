<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kegiatan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Tambah Kegiatan</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan:</label>
                <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
            </div>
            <div class="form-group">
                <label for="rincian_kegiatan">Rincian Kegiatan:</label>
                <textarea class="form-control" id="rincian_kegiatan" name="rincian_kegiatan" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
                <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
            </div>
            <div class="form-group">
                <img id="preview" src="#" alt="Preview Foto" style="display: none; max-width: 300px; margin-top: 10px;">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
