<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kegiatan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .center-image {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Kegiatan</h1>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createKegiatanModal">
            Tambah Kegiatan
        </button>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Rincian Kegiatan</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatans as $kegiatan)
                    <tr>
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
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editKegiatanModal{{ $kegiatan->id }}">
                                Edit
                            </button>
                            <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            <a href="{{ route('kegiatan.print', $kegiatan->id) }}" class="btn btn-info btn-sm" target="_blank">
                                Cetak
                            </a>
                        </td>
                    </tr>

                    <!-- Edit Kegiatan Modal -->
                    <div class="modal fade" id="editKegiatanModal{{ $kegiatan->id }}" tabindex="-1" aria-labelledby="editKegiatanModalLabel{{ $kegiatan->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editKegiatanModalLabel{{ $kegiatan->id }}">Edit Kegiatan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="nama_kegiatan{{ $kegiatan->id }}">Nama Kegiatan:</label>
                                            <input type="text" class="form-control" id="nama_kegiatan{{ $kegiatan->id }}" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="rincian_kegiatan{{ $kegiatan->id }}">Rincian Kegiatan:</label>
                                            <textarea class="form-control" id="rincian_kegiatan{{ $kegiatan->id }}" name="rincian_kegiatan" rows="4" required>{{ $kegiatan->rincian_kegiatan }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_kegiatan{{ $kegiatan->id }}">Tanggal Kegiatan:</label>
                                            <input type="date" class="form-control" id="tanggal_kegiatan{{ $kegiatan->id }}" name="tanggal_kegiatan" value="{{ $kegiatan->tanggal_kegiatan }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="foto{{ $kegiatan->id }}">Foto:</label>
                                            <input type="file" class="form-control-file" id="foto{{ $kegiatan->id }}" name="fotos[]" accept="image/*" multiple onchange="previewImages(event, 'editPreview{{ $kegiatan->id }}')">
                                        </div>
                                        <div class="form-group">
                                            <div id="editPreview{{ $kegiatan->id }}"></div> <!-- Container for image preview -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Kegiatan Modal -->
    <div class="modal fade" id="createKegiatanModal" tabindex="-1" aria-labelledby="createKegiatanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createKegiatanModalLabel">Tambah Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                            <input type="file" class="form-control-file" id="foto" name="fotos[]" accept="image/*" multiple onchange="previewImages(event, 'createPreview')">
                        </div>
                        <div class="form-group">
                            <div id="createPreview"></div> <!-- Container for image preview -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
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
    </script>
</body>
</html>
