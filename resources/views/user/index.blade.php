@extends('layouts.main')

@section('title', 'Daftar Pengguna')

@section('content')
<div class="container">
    <h2>Daftar Pengguna</h2>

    <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#tambahUserModal">Tambah Pengguna</button>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Level</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->level == 1 ? 'Administrator' : 'User' }}</td>
                <td>
                    @if($user->foto_pengguna)
                        <img src="{{ asset('storage/foto_pengguna/' . $user->foto_pengguna) }}" alt="Foto Pengguna" class="img-thumbnail" style="width: 100px;">
                    @else
                        <p>Tidak ada foto</p>
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> --}}

<!-- Modal Tambah Pengguna -->
@include('user.create')

<!-- Modal Edit Pengguna -->
@include('user.edit')

@endsection

@push('scripts')
<script>
    // Fungsi untuk mengambil data pengguna berdasarkan ID
    function getUserById(id) {
        var users = {!! json_encode($users) !!};
        return users.find(user => user.id == id);
    }

    // Fungsi untuk menampilkan modal edit dan mengisi data pengguna ke dalam form
    function editUser(id) {
        var user = getUserById(id);
        $('#edit_name').val(user.name);
        $('#edit_email').val(user.email);
        $('#edit_level').val(user.level);
        $('#edit_id').val(user.id);
        $('#edit_image_preview').attr('src', '{{ asset("storage/foto_pengguna") }}' + '/' + user.foto_pengguna);
        $('#editUserModal').modal('show');
    }

    // Fungsi untuk menghapus pengguna
    function deleteUser(id) {
        if (confirm('Anda yakin ingin menghapus pengguna ini?')) {
            $.ajax({
                type: 'DELETE',
                url: '/user/' + id,
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
    }
</script>
@endpush
