<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Foto;
use PDF;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Intervention\Image\Facades\Image;

class KegiatanController extends Controller
{
    public function index()
    {
        //dd('KegiatanController@index: Accessing kegiatan index');
        
        $userLevel = auth()->user()->level;
        if ($userLevel == 1) {
            $kegiatans = Kegiatan::all();
        } else {
            $kegiatans = Kegiatan::where('user_id', auth()->id())->get();
        }
        //Log::info('KegiatanController@index: Accessing kegiatan index');
        //dd($kegiatans);
        return view('kegiatan.index', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'rincian_kegiatan' => 'required',
            'tanggal_kegiatan' => 'required|date',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi untuk setiap foto
        ]);
    
        $kegiatan = new Kegiatan();
        $kegiatan->nama_kegiatan = $request->nama_kegiatan;
        $kegiatan->rincian_kegiatan = $request->rincian_kegiatan;
        $kegiatan->tanggal_kegiatan = $request->tanggal_kegiatan;
        $kegiatan->user_id = auth()->id(); // Menetapkan user_id dari pengguna yang sedang login
        $kegiatan->save();
    
        // Simpan foto jika ada
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                // Resize dan kompresi gambar
                $image = Image::make($foto)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg', 75); // Kompresi gambar dengan kualitas 75%
    
                // Simpan gambar ke dalam folder storage/public/foto_kegiatan/ dengan nama yang unik
                $path = $foto->store('foto_kegiatan', 'public');
    
                // Ambil nama file dari path yang dihasilkan oleh metode store
                $fileName = basename($path);
    
                // Buat path relatif untuk penyimpanan di database
                $path = 'foto_kegiatan/' . $fileName;
    
                // Buat model Foto dan simpan ke database
                $kegiatan->fotos()->create(['nama_file' => $path]);
            }
        }
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kegiatan' => 'required|max:255',
            'rincian_kegiatan' => 'required',
            'tanggal_kegiatan' => 'required|date',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan kebutuhan
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        // Memeriksa apakah pengguna memiliki akses untuk mengedit kegiatan
        if (!$this->hasAccess($kegiatan)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
        }

        $kegiatan->update($request->except('fotos'));

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                // Resize dan kompresi gambar
                $image = Image::make($foto)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg', 75); // Kompresi gambar dengan kualitas 75%

                // Simpan gambar yang telah diresize
                $path = $foto->store('foto_kegiatan', 'public');

                // Buat model Foto dan simpan ke database
                $kegiatan->fotos()->create([
                    'nama_file' => $path
                ]);
            }
        }

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diedit.');
    }

    public function print($id)
    {
        set_time_limit(300);
        $kegiatan = Kegiatan::findOrFail($id);
        $fotos = $kegiatan->fotos;
    
        // Mengoptimalkan gambar sebelum membuat PDF
        foreach ($fotos as $foto) {
            $this->optimizeImage(storage_path('app/public/' . $foto->nama_file));
        }
    
        $pdf = PDF::loadView('kegiatan.print', compact('kegiatan', 'fotos'));
        return $pdf->stream('kegiatan-'.$kegiatan->id.'.pdf');
    }
    
    private function optimizeImage($path)
    {
        $img = Image::make($path);
    
        // Ubah ukuran gambar jika terlalu besar
        if ($img->width() > 1200) {
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
    
        $img->save($path, 75); // Simpan gambar dengan kualitas 75%
    }

    public function destroy($id)
    {
        // Temukan kegiatan berdasarkan ID atau lemparkan pengecualian jika tidak ditemukan
        $kegiatan = Kegiatan::findOrFail($id);
    
        // Memeriksa apakah pengguna memiliki akses untuk menghapus kegiatan
        if (!$this->hasAccess($kegiatan)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kegiatan ini.');
        }
    
        // Hapus setiap foto terkait dengan kegiatan
        foreach ($kegiatan->fotos as $foto) {
            // Dapatkan path lengkap ke foto
            $fotoPath = storage_path('app/public/' . $foto->nama_file);
    
            // Hapus file foto jika ada
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
    
            // Hapus record foto dari database
            $foto->delete();
        }
    
        // Hapus kegiatan dari database
        $kegiatan->delete();
    
        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    // Tambahkan fungsi berikut untuk mengecek apakah pengguna memiliki akses ke kegiatan tertentu
    private function hasAccess($kegiatan)
    {
        $userLevel = auth()->user()->level;
        if ($userLevel == 1) {
            // Jika pengguna adalah administrator, beri akses ke semua kegiatan
            return true;
        } else {
            // Jika pengguna adalah pengguna biasa, periksa apakah kegiatan dibuat oleh pengguna itu sendiri
            return $kegiatan->user_id == auth()->id();
        }
    }
}