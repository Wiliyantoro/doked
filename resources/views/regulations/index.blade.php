@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Peraturan</h1>
        <a href="{{ route('regulations.create') }}" class="btn btn-primary">Buat Peraturan</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($regulations as $regulation)
                    <tr>
                        <td>{{ $regulation->name }}</td>
                        <td>{{ $regulation->type }}</td>
                        <td>
                            <a href="{{ route('regulations.show', $regulation) }}" class="btn btn-info">View</a>
                            <a href="{{ route('regulations.edit', $regulation) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('regulations.destroy', $regulation) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
