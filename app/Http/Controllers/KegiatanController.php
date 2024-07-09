<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Foto;
use PDF;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class KegiatanController extends Controller
{
    public function index()
    {
        $userLevel = auth()->user()->level;
        if ($userLevel == 1) {
            $kegiatans = Kegiatan::all();
        } else {
            $kegiatans = Kegiatan::where('user_id', auth()->id())->get();
        }

        return view('kegiatan.index', compact('kegiatans', 'userLevel'));
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kegiatan.edit', compact('kegiatan'));
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'rincian_kegiatan' => 'required',
            'tanggal_kegiatan' => 'required|date',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);
    
        $kegiatan = new Kegiatan();
        $kegiatan->nama_kegiatan = $request->nama_kegiatan;
        $kegiatan->rincian_kegiatan = $request->rincian_kegiatan;
        $kegiatan->tanggal_kegiatan = $request->tanggal_kegiatan;
        $kegiatan->user_id = auth()->id();
        $kegiatan->save();
    
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $image = Image::make($foto)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg', 75);
    
                $path = 'foto_kegiatan/' . uniqid() . '.jpg';
                Storage::disk('public')->put($path, $image);
    
                $kegiatan->fotos()->create(['nama_file' => $path]);
            }
        }
    
        if ($request->has('camera_photos')) {
            foreach ($request->camera_photos as $cameraPhoto) {
                $image = Image::make($cameraPhoto)->encode('jpg', 75);
    
                $path = 'foto_kegiatan/' . uniqid() . '.jpg';
                Storage::disk('public')->put($path, $image);
    
                $kegiatan->fotos()->create(['nama_file' => $path]);
            }
        }
    
        // Mengembalikan respons JSON yang benar
        return response()->json(['success' => true, 'message' => 'Kegiatan berhasil ditambah'], 200);
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'rincian_kegiatan' => 'required',
            'tanggal_kegiatan' => 'required|date',
            'replaced_fotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'camera_photos.*' => 'nullable|string', // validasi untuk base64
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Validasi untuk foto baru
        ]);
    
        $kegiatan = Kegiatan::findOrFail($id);
    
        // Update data kegiatan
        $kegiatan->nama_kegiatan = $request->nama_kegiatan;
        $kegiatan->rincian_kegiatan = $request->rincian_kegiatan;
        $kegiatan->tanggal_kegiatan = $request->tanggal_kegiatan;
        $kegiatan->save();
    
        // Mengganti foto lama dengan foto baru dari input file
        if ($request->hasFile('replaced_fotos')) {
            foreach ($request->file('replaced_fotos') as $fotoId => $file) {
                $this->replaceFoto($fotoId, $file, $kegiatan->id);
            }
        }
    
        // Mengganti foto lama dengan foto baru dari kamera (base64)
        if ($request->has('camera_photos')) {
            foreach ($request->camera_photos as $fotoId => $base64Image) {
                if ($base64Image) { // Pastikan base64Image tidak kosong
                    $this->replaceFotoBase64($fotoId, $base64Image, $kegiatan->id);
                }
            }
        }
            // Menyimpan foto baru dari new-photos-preview
        if ($request->has('fotos')) {
        foreach ($request->fotos as $photo) {
            $this->saveNewPhoto($photo, $kegiatan->id);
        }
    }
    
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diupdate.');
    }
    
    private function replaceFoto($fotoId, $file, $kegiatanId)
    {
        // Hapus foto lama
        $foto = Foto::findOrFail($fotoId);
        $path = storage_path('app/public/' . $foto->nama_file);
        if (file_exists($path)) {
            unlink($path);
        }
        $foto->delete();
    
        // Simpan foto baru di storage
        $newPath = 'foto_kegiatan/' . uniqid() . '.' . $file->getClientOriginalExtension();
        $image = Image::make($file)->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg', 75);
        Storage::disk('public')->put($newPath, $image);
    
        // Tambahkan record baru ke database
        Foto::create([
            'kegiatan_id' => $kegiatanId,
            'nama_file' => $newPath,
        ]);
    }
    
    private function replaceFotoBase64($fotoId, $base64Image, $kegiatanId)
    {
        // Hapus foto lama
        $foto = Foto::findOrFail($fotoId);
        $path = storage_path('app/public/' . $foto->nama_file);
        if (file_exists($path)) {
            unlink($path);
        }
        $foto->delete();
    
        // Decode base64 image
        $image = Image::make($base64Image)->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg', 75);
    
        // Simpan foto baru di storage
        $newPath = 'foto_kegiatan/' . uniqid() . '.jpg';
        Storage::disk('public')->put($newPath, $image);
    
        // Tambahkan record baru ke database
        Foto::create([
            'kegiatan_id' => $kegiatanId,
            'nama_file' => $newPath,
        ]);
    }

    private function saveNewPhoto($base64Image, $kegiatanId)
    {
        // Decode base64 image
        $image = Image::make($base64Image)->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg', 75);

        // Simpan foto baru di storage
        $newPath = 'foto_kegiatan/' . uniqid() . '.jpg';
        Storage::disk('public')->put($newPath, $image);

        // Tambahkan record baru ke database
        Foto::create([
            'kegiatan_id' => $kegiatanId,
            'nama_file' => $newPath,
        ]);
    }
    

    public function print($id)
    {
        set_time_limit(300);
        $kegiatan = Kegiatan::findOrFail($id);
        $fotos = $kegiatan->fotos;

        foreach ($fotos as $foto) {
            $this->optimizeImage(storage_path('app/public/' . $foto->nama_file));
        }

        $pdf = PDF::loadView('kegiatan.print', compact('kegiatan', 'fotos'));
        return $pdf->stream('kegiatan-'.$kegiatan->id.'.pdf');
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

    public function deleteFoto($id)
    {
        $foto = Foto::findOrFail($id);

        $path = storage_path('app/public/' . $foto->nama_file);

        // Hapus dari storage
        if (file_exists($path)) {
            unlink($path);
        }

        // Hapus dari database
        $foto->delete();

        // Mengirim response JSON yang sukses
        return response()->json(['message' => 'Foto berhasil dihapus'], 200);
    }


    private function optimizeImage($path)
    {
        $img = Image::make($path);

        if ($img->width() > 1200) {
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $img->save($path, 75);
    }
}

