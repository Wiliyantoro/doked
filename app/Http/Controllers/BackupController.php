<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use DB;

class BackupController extends Controller
{
    public function index()
    {
        $backupFiles = Storage::files('backups');
        return view('settings.index', compact('backupFiles'));
    }

    public function backupDatabase()
    {
        // Nama file backup
        $backupFileName = 'database_backup_' . date('Ymd_His') . '.sql';
    
        // Path untuk menyimpan file backup
        $backupFilePath = storage_path('app/backups/' . $backupFileName);

        // Menjalankan mysqldump
        $command = sprintf(
            'mysqldump --host=%s --user=%s --no-tablespaces --databases %s > %s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.database'),
            $backupFilePath
        );

        exec($command, $output, $resultCode);

        // Periksa apakah backup berhasil dibuat
        if ($resultCode === 0) {
            // Jika berhasil, kirim file backup untuk di-download
            return response()->download($backupFilePath)->deleteFileAfterSend(true);
        } else {
            // Jika gagal, beri pesan error
            return back()->with('error', 'Failed to create database backup. Check log for details.');
        }
    }

    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql',
        ]);

        // Restore database
        $backupFile = $request->file('backup_file');
        $backupFile->storeAs('backups', $backupFile->getClientOriginalName(), 'local');
        $backupPath = storage_path('app/backups/' . $backupFile->getClientOriginalName());
        $sql = file_get_contents($backupPath);
        DB::unprepared($sql);

        return back()->with('success', 'Database restored successfully.');
    }

    public function backupData()
    {
        $zip = new ZipArchive;
        $fileName = 'data_backup_' . date('Y-m-d_His') . '.zip';
        $filePath = storage_path('app/backups/' . $fileName);

        if ($zip->open($filePath, ZipArchive::CREATE) === TRUE) {
            $files = Storage::allFiles('public');
            foreach ($files as $file) {
                $relativeNameInZipFile = str_replace('public/', '', $file);
                $zip->addFile(storage_path('app/' . $file), $relativeNameInZipFile);
            }
            $zip->close();
            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'Failed to create data backup.');
        }
    }

    public function restoreData(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip',
        ]);

        // Restore data
        $backupFile = $request->file('backup_file');
        $backupFile->storeAs('backups', $backupFile->getClientOriginalName(), 'local');
        $backupPath = storage_path('app/backups/' . $backupFile->getClientOriginalName());

        $zip = new ZipArchive;
        if ($zip->open($backupPath) === TRUE) {
            $zip->extractTo(storage_path('app/public'));
            $zip->close();
            return back()->with('success', 'Data restored successfully.');
        } else {
            return back()->with('error', 'Failed to restore data.');
        }
    }

    public function deleteBackup($fileName)
    {
        if (Storage::exists('backups/' . $fileName)) {
            Storage::delete('backups/' . $fileName);
            return back()->with('success', 'Backup deleted successfully.');
        } else {
            return back()->with('error', 'Backup not found.');
        }
    }
}
