@extends('layouts.main')

@section('title', 'Settings')

@section('content')
<div class="container mt-5">
    <h1>Settings</h1>

    {{-- Backup Database --}}
    <div class="card mt-3">
        <div class="card-header">
            Backup Database
        </div>
        <div class="card-body">
            <form action="{{ route('settings.backupDatabase') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Backup Database</button>
            </form>
        </div>
    </div>

    {{-- Restore Database --}}
    <div class="card mt-3">
        <div class="card-header">
            Restore Database
        </div>
        <div class="card-body">
            <form action="{{ route('settings.restoreDatabase') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="backup_file">Upload Backup File (.sql)</label>
                    <input type="file" class="form-control-file" id="backup_file" name="backup_file" required>
                </div>
                <button type="submit" class="btn btn-primary">Restore Database</button>
            </form>
        </div>
    </div>

    {{-- Backup Data --}}
    <div class="card mt-3">
        <div class="card-header">
            Backup Data (All files in public folder)
        </div>
        <div class="card-body">
            <form action="{{ route('settings.backupData') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Backup Data</button>
            </form>
        </div>
    </div>

    {{-- Restore Data --}}
    <div class="card mt-3">
        <div class="card-header">
            Restore Data (All files in public folder)
        </div>
        <div class="card-body">
            <form action="{{ route('settings.restoreData') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="backup_file">Upload Backup File (.zip)</label>
                    <input type="file" class="form-control-file" id="backup_file" name="backup_file" required>
                </div>
                <button type="submit" class="btn btn-primary">Restore Data</button>
            </form>
        </div>
    </div>

    {{-- List of Backups --}}
    <div class="card mt-3">
        <div class="card-header">
            Backup Files
        </div>
        <div class="card-body">
            @if($backupFiles)
                <ul class="list-group">
                    @foreach($backupFiles as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ basename($file) }}
                            <div>
                                <a href="{{ route('settings.downloadBackup', ['file' => basename($file)]) }}" class="btn btn-sm btn-success">Download</a>
                                <form action="{{ route('settings.deleteBackup', ['file' => basename($file)]) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No backup files found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
