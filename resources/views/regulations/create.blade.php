@extends('layouts.main')

@section('title', 'Tambah Peraturan')

@section('content')
    <div class="container mt-5">
        <h1>Tambah Peraturan Baru</h1>
        <form action="{{ route('regulations.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Peraturan:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="type">Jenis Peraturan:</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="Perdes">Perdes (Peraturan Desa)</option>
                    <option value="Perkel">Perkel (Peraturan Perbekel)</option>
                    <option value="Keputusan Perbekel">Keputusan Perbekel</option>
                </select>
            </div>
            <div class="form-group">
                <label for="regulation_number">Nomor Peraturan:</label>
                <input type="text" id="regulation_number" name="regulation_number" class="form-control" required>
            </div>
            <hr>
            <div class="form-group">
                <label for="menimbang">Isi Menimbang:</label>
                <div id="menimbang-container">
                    <div class="input-group mb-2">
                        <textarea name="menimbang[]" class="form-control" rows="2" required></textarea>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-menimbang">Hapus</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="addMenimbang" class="btn btn-secondary">Tambah Menimbang</button>
            </div>
            <div class="form-group">
                <label for="mengingat">Isi Mengingat:</label>
                <div id="mengingat-container">
                    <div class="input-group mb-2">
                        <textarea name="mengingat[]" class="form-control" rows="2" required></textarea>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-mengingat">Hapus</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="addMengingat" class="btn btn-secondary">Tambah Mengingat</button>
            </div>
            <div class="form-group">
                <label for="memutuskan">Isi Putusan:</label>
                <div id="putusan-container">
                    <div class="putusan-group mb-3">
                        <input type="text" name="memutuskan[0][title]" class="form-control" placeholder="Judul Putusan" required>
                        <textarea name="memutuskan[0][content]" class="form-control" rows="2" placeholder="Isi Putusan" required></textarea>
                        <small class="form-text text-muted">Tambahkan sub-putusan dengan menekan Enter setelah setiap entri.</small>
                        <div class="sub-putusan-container">
                            <input type="text" name="memutuskan[0][sub][]" class="form-control mt-2" placeholder="Sub Putusan (opsional)">
                        </div>
                        <button type="button" class="btn btn-secondary mt-2 add-sub-putusan">Tambah Sub Putusan</button>
                        <div class="input-group-append mt-2">
                            <button type="button" class="btn btn-danger remove-putusan">Hapus Putusan</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="addPutusan" class="btn btn-secondary">Tambah Putusan</button>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let putusanIndex = 1;

            // Add menimbang
            $('#addMenimbang').click(function() {
                var newMenimbang = `
                <div class="input-group mb-2">
                    <textarea name="menimbang[]" class="form-control" rows="2" required></textarea>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-menimbang">Hapus</button>
                    </div>
                </div>`;
                $('#menimbang-container').append(newMenimbang);
            });

            // Add mengingat
            $('#addMengingat').click(function() {
                var newMengingat = `
                <div class="input-group mb-2">
                    <textarea name="mengingat[]" class="form-control" rows="2" required></textarea>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-mengingat">Hapus</button>
                    </div>
                </div>`;
                $('#mengingat-container').append(newMengingat);
            });

            // Add putusan
            $('#addPutusan').click(function() {
                var newPutusanGroup = `
                <div class="putusan-group mb-3">
                    <input type="text" name="memutuskan[${putusanIndex}][title]" class="form-control" placeholder="Judul Putusan" required>
                    <textarea name="memutuskan[${putusanIndex}][content]" class="form-control" rows="2" placeholder="Isi Putusan" required></textarea>
                    <small class="form-text text-muted">Tambahkan sub-putusan dengan menekan Enter setelah setiap entri.</small>
                    <div class="sub-putusan-container">
                        <input type="text" name="memutuskan[${putusanIndex}][sub][]" class="form-control mt-2" placeholder="Sub Putusan (opsional)">
                    </div>
                    <button type="button" class="btn btn-secondary mt-2 add-sub-putusan">Tambah Sub Putusan</button>
                    <div class="input-group-append mt-2">
                        <button type="button" class="btn btn-danger remove-putusan">Hapus Putusan</button>
                    </div>
                </div>`;
                $('#putusan-container').append(newPutusanGroup);
                putusanIndex++;
            });

            // Add sub-putusan
            $(document).on('click', '.add-sub-putusan', function() {
                var subPutusanContainer = $(this).siblings('.sub-putusan-container');
                var newSubPutusan = `<input type="text" name="memutuskan[${putusanIndex - 1}][sub][]" class="form-control mt-2" placeholder="Sub Putusan (opsional)">`;
                subPutusanContainer.append(newSubPutusan);
            });

            // Remove menimbang
            $(document).on('click', '.remove-menimbang', function() {
                $(this).closest('.input-group').remove();
            });

            // Remove mengingat
            $(document).on('click', '.remove-mengingat', function() {
                $(this).closest('.input-group').remove();
            });

            // Remove putusan
            $(document).on('click', '.remove-putusan', function() {
                $(this).closest('.putusan-group').remove();
            });
        });
    </script>
@endpush
