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
        return view('settings.index');
    }

    public function backupDatabase()
    {
        // Pastikan folder backups ada
        $backupFolder = storage_path('app/backups');
        if (!file_exists($backupFolder)) {
            mkdir($backupFolder, 0777, true);
        }

        // Nama file backup
        $backupFileName = 'database_backup_' . date('Ymd_His') . '.sql';
    
        // Path untuk menyimpan file backup
        $backupFilePath = $backupFolder . '/' . $backupFileName;
    
        // Command untuk menjalankan mysqldump
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s --no-tablespaces --databases %s > %s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $backupFilePath
        );
        //dd($command);
    
        // Jalankan perintah untuk membuat backup
        exec($command, $output, $resultCode);
        
    
        // Periksa apakah backup berhasil dibuat
        if ($resultCode === 0) {
            // Jika berhasil, kirim file backup untuk di-download
            return response()->download($backupFilePath)->deleteFileAfterSend(true);
        } else {
            // Jika gagal, beri pesan error
            return back()->with('error', 'Failed to create database backup.');
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
        // Pastikan folder backups ada
        $backupFolder = storage_path('app/backups');
        if (!file_exists($backupFolder)) {
            mkdir($backupFolder, 0777, true);
        }

        $zip = new ZipArchive;
        $fileName = 'data_backup_' . date('Y-m-d_His') . '.zip';
        $filePath = $backupFolder . '/' . $fileName;

        if ($zip->open($filePath, ZipArchive::CREATE) === TRUE) {
            $files = Storage::allFiles('public');
            foreach ($files as $file) {
                $zip->addFile(storage_path('app/' . $file), $file);
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
}
