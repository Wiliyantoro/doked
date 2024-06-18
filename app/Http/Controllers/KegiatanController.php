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
        $kegiatans = Kegiatan::all();
        return view('kegiatan.index', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'rincian_kegiatan' => 'required',
            'tanggal_kegiatan' => 'required|date',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Sesuaikan kebutuhan
        ]);
    
        $kegiatan = Kegiatan::create($request->except('fotos'));
    
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                // Resize dan kompresi gambar
                $image = Image::make($foto)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg', 75); // Kompresi gambar dengan kualitas 75%
    
                // Simpan gambar yang telah diresize ke path sementara
                $path = 'foto_kegiatan/' . uniqid() . '.jpg';
                Storage::disk('public')->put($path, $image);
    
                // Buat model Foto dan simpan ke database
                $kegiatan->fotos()->create([
                    'nama_file' => $path
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Kegiatan berhasil ditambahkan.');
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

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
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
        $kegiatan = Kegiatan::findOrFail($id);

        foreach ($kegiatan->fotos as $foto) {
            $fotoPath = storage_path('app/public/' . $foto->nama_file);
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
            $foto->delete();
        }

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}

