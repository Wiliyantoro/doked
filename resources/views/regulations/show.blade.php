@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Detail Peraturan</h1>
        <a href="{{ route('regulations.index') }}" class="btn btn-secondary">Kembali</a>
        
        <div class="card mt-3">
            <div class="card-header">
                <h2>{{ $regulation->name }}</h2>
                <p>{{ $regulation->type }} - Nomor: {{ $regulation->regulation_number }}</p>
            </div>
            <div class="card-body">
                <h3>Menimbang
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addMenimbangModal">Tambah Menimbang</button>
                </h3>
                <ul>
                    @foreach($regulation->menimbang as $menimbang)
                        <li>
                            <form>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="{{ $menimbang->content }}" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMenimbangModal{{ $menimbang->id }}">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="modal" data-target="#confirmDeleteMenimbangModal{{ $menimbang->id }}">Hapus</button>
                                    </div>
                                </div>
                            </form>
                        </li>
                        <!-- Modal Konfirmasi Hapus Menimbang -->
                        <div class="modal fade" id="confirmDeleteMenimbangModal{{ $menimbang->id }}" tabindex="-1" aria-labelledby="confirmDeleteMenimbangModalLabel{{ $menimbang->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteMenimbangModalLabel{{ $menimbang->id }}">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus menimbang ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('regulations.deleteMenimbang', $menimbang->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
                
                <h3>Mengingat
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addMengingatModal">Tambah Mengingat</button>
                </h3>
                <ul>
                    @foreach($regulation->mengingat as $mengingat)
                        <li>
                            <form>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="{{ $mengingat->content }}" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMengingatModal{{ $mengingat->id }}">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="modal" data-target="#confirmDeleteMengingatModal{{ $mengingat->id }}">Hapus</button>
                                    </div>
                                </div>
                            </form>
                        </li>
                        <!-- Modal Konfirmasi Hapus Mengingat -->
                        <div class="modal fade" id="confirmDeleteMengingatModal{{ $mengingat->id }}" tabindex="-1" aria-labelledby="confirmDeleteMengingatModalLabel{{ $mengingat->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteMengingatModalLabel{{ $mengingat->id }}">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus mengingat ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('regulations.deleteMengingat', $mengingat->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
                
                <h3>Memutuskan
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addMemutuskanModal">Tambah Putusan</button>
                </h3>
                @foreach($regulation->memutuskan as $memutuskan)
                    <div class="mb-3">
                        <form>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" value="{{ $memutuskan->title }}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMemutuskanModal{{ $memutuskan->id }}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="modal" data-target="#confirmDeleteMemutuskanModal{{ $memutuskan->id }}">Hapus</button>
                                </div>
                            </div>
                            <textarea class="form-control" rows="2" readonly>{{ $memutuskan->content }}</textarea>
                        </form>
                        <h5>Sub Memutuskan
                            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addSubMemutuskanModal{{ $memutuskan->id }}">Tambah Sub Putusan</button>
                        </h5>
                        @if($memutuskan->subMemutuskan->isNotEmpty())
                            <ul>
                                @foreach($memutuskan->subMemutuskan as $sub)
                                    <li>
                                        <form>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" value="{{ $sub->content }}" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSubMemutuskanModal{{ $sub->id }}">Edit</button>
                                                    <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="modal" data-target="#confirmDeleteSubMemutuskanModal{{ $sub->id }}">Hapus</button>
                                                </div>
                                            </div>
                                        </form>
                                    </li>
                                    <!-- Modal Konfirmasi Hapus Sub Memutuskan -->
                                    <div class="modal fade" id="confirmDeleteSubMemutuskanModal{{ $sub->id }}" tabindex="-1" aria-labelledby="confirmDeleteSubMemutuskanModalLabel{{ $sub->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmDeleteSubMemutuskanModalLabel{{ $sub->id }}">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus sub memutuskan ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <form action="{{ route('regulations.deleteSubMemutuskan', $sub->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <!-- Modal Konfirmasi Hapus Memutuskan -->
                    <div class="modal fade" id="confirmDeleteMemutuskanModal{{ $memutuskan->id }}" tabindex="-1" aria-labelledby="confirmDeleteMemutuskanModalLabel{{ $memutuskan->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteMemutuskanModalLabel{{ $memutuskan->id }}">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus memutuskan ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <form action="{{ route('regulations.deleteMemutuskan', $memutuskan->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Tambah Menimbang -->
    <div class="modal fade" id="addMenimbangModal" tabindex="-1" role="dialog" aria-labelledby="addMenimbangModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenimbangModalLabel">Tambah Menimbang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('regulations.addMenimbang', $regulation->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="menimbang">Isi Menimbang</label>
                            <textarea name="content" class="form-control" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Menimbang -->
    @foreach($regulation->menimbang as $menimbang)
    <div class="modal fade" id="editMenimbangModal{{ $menimbang->id }}" tabindex="-1" role="dialog" aria-labelledby="editMenimbangModalLabel{{ $menimbang->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenimbangModalLabel{{ $menimbang->id }}">Edit Menimbang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('regulations.editMenimbang', $menimbang->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="menimbang">Isi Menimbang</label>
                            <textarea name="content" class="form-control" rows="2" required>{{ $menimbang->content }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Tambah Mengingat -->
    <div class="modal fade" id="addMengingatModal" tabindex="-1" role="dialog" aria-labelledby="addMengingatModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMengingatModalLabel">Tambah Mengingat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('regulations.addMengingat', $regulation->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="mengingat">Isi Mengingat</label>
                            <textarea name="content" class="form-control" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Mengingat -->
    @foreach($regulation->mengingat as $mengingat)
    <div class="modal fade" id="editMengingatModal{{ $mengingat->id }}" tabindex="-1" role="dialog" aria-labelledby="editMengingatModalLabel{{ $mengingat->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMengingatModalLabel{{ $mengingat->id }}">Edit Mengingat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('regulations.editMengingat', $mengingat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="mengingat">Isi Mengingat</label>
                            <textarea name="content" class="form-control" rows="2" required>{{ $mengingat->content }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Tambah Memutuskan -->
    <div class="modal fade" id="addMemutuskanModal" tabindex="-1" role="dialog" aria-labelledby="addMemutuskanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemutuskanModalLabel">Tambah Memutuskan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('regulations.addMemutuskan', $regulation->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="memutuskan">Judul Memutuskan</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="memutuskan">Isi Memutuskan</label>
                            <textarea name="content" class="form-control" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Memutuskan -->
    @foreach($regulation->memutuskan as $memutuskan)
    <div class="modal fade" id="editMemutuskanModal{{ $memutuskan->id }}" tabindex="-1" role="dialog" aria-labelledby="editMemutuskanModalLabel{{ $memutuskan->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMemutuskanModalLabel{{ $memutuskan->id }}">Edit Memutuskan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('regulations.editMemutuskan', $memutuskan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="memutuskan">Judul Memutuskan</label>
                            <input type="text" name="title" class="form-control" value="{{ $memutuskan->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="memutuskan">Isi Memutuskan</label>
                            <textarea name="content" class="form-control" rows="2" required>{{ $memutuskan->content }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Tambah Sub Putusan -->
    @foreach($regulation->memutuskan as $memutuskan)
    <div class="modal fade" id="addSubMemutuskanModal{{ $memutuskan->id }}" tabindex="-1" role="dialog" aria-labelledby="addSubMemutuskanModalLabel{{ $memutuskan->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubMemutuskanModalLabel{{ $memutuskan->id }}">Tambah Sub Putusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('regulations.addSubMemutuskan', $memutuskan->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="content">Isi Sub Putusan</label>
                            <textarea name="content" class="form-control" rows="2" required></textarea>
                        </div>
                        <input type="hidden" name="memutuskan_id" value="{{ $memutuskan->id }}">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Edit Sub Memutuskan -->
    @foreach($regulation->memutuskan as $memutuskan)
        @foreach($memutuskan->subMemutuskan as $sub)
            <div class="modal fade" id="editSubMemutuskanModal{{ $sub->id }}" tabindex="-1" role="dialog" aria-labelledby="editSubMemutuskanModalLabel{{ $sub->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSubMemutuskanModalLabel{{ $sub->id }}">Edit Sub Memutuskan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('regulations.editSubMemutuskan', $sub->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="subMemutuskan">Isi Sub Memutuskan</label>
                                    <textarea name="content" class="form-control" rows="2" required>{{ $sub->content }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

@endsection
