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
    
        // Menjalankan mysqldump dengan argumen --add-drop-table untuk menyertakan perintah DROP TABLE
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s --no-tablespaces --add-drop-table --databases %s > %s',
            escapeshellarg(config('database.connections.mysql.host')),
            escapeshellarg(config('database.connections.mysql.username')),
            escapeshellarg(config('database.connections.mysql.password')),
            escapeshellarg(config('database.connections.mysql.database')),
            escapeshellarg($backupFilePath)
        );
    
        // Jalankan command dan tangkap outputnya
        exec($command, $output, $resultCode);
    
        // Log hasil exec untuk debugging
        \Log::info('Mysqldump command output: ' . print_r($output, true));
        \Log::info('Mysqldump command result code: ' . $resultCode);
    
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
        // Validasi file backup
        $request->validate([
            'backup_file' => 'required|file|mimes:sql',
        ]);
        // dd($request->file('backup_file'));
        // Path untuk menyimpan file backup di server
        $backupFile = $request->file('backup_file');
        // Simpan file backup di direktori lokal
        $backupFile->storeAs('backups', $backupFile->getClientOriginalName());
        $backupPath = storage_path('app/backups/' . $backupFile->getClientOriginalName());
    
    
        // Log untuk memastikan file SQL sudah disimpan
        if (file_exists($backupPath)) {
            \Log::info('Backup file saved successfully: ' . $backupPath);
        } else {
            \Log::error('Backup file not found after moving: ' . $backupPath);
            return back()->with('error', 'Failed to save backup file.');
        }
    
        // Eksekusi perintah restore menggunakan command shell
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
    
        // Perintah untuk melakukan restore database
        $command = sprintf(
            'mysql --host=%s --user=%s --password=%s %s < %s',
            escapeshellarg(config('database.connections.mysql.host')),
            escapeshellarg(config('database.connections.mysql.username')),
            escapeshellarg(config('database.connections.mysql.password')),
            escapeshellarg($database),
            escapeshellarg($backupPath)
        );
    
        // Jalankan perintah menggunakan exec
        exec($command, $output, $returnVar);
    
        // Log hasil exec untuk debugging
        \Log::info('MySQL Restore Command: ' . $command);
        \Log::info('MySQL Restore Output: ' . print_r($output, true));
        \Log::info('MySQL Restore Return Code: ' . $returnVar);
    
        // Periksa hasil eksekusi
        if ($returnVar !== 0) {
            // Jika ada kesalahan, tangani di sini
            return back()->with('error', 'Gagal melakukan restore database.');
        }
    
        // Sukses restore database
        return back()->with('success', 'Database berhasil dipulihkan.');
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
