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
                        <input type="file" class="form-control-file" id="foto{{ $kegiatan->id }}" name="fotos[]" accept="image/*" onchange="previewImages(event, 'editPreview{{ $kegiatan->id }}')" multiple>
                        <div id="editPreview{{ $kegiatan->id }}">
                            @foreach($kegiatan->fotos as $foto)
                                <img src="{{ url('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan" style="max-width: 100px;">
                            @endforeach
                        </div>
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
