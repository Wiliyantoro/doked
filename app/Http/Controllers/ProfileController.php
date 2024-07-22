<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        // Validasi data
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'foto_pengguna' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Jika password baru diisi, tambahkan validasi
        if ($request->filled('password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Cek kata sandi lama jika password baru diisi
        if ($request->filled('password') && !\Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi lama salah.']);
        }

        // Update user data
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        // Perbarui password jika diisi
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        // Handle foto_pengguna
        if ($request->hasFile('foto_pengguna')) {
            // Hapus foto lama jika ada
            if ($user->foto_pengguna) {
                $oldPhotoPath = public_path('storage/foto_pengguna/' . $user->foto_pengguna);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $file = $request->file('foto_pengguna');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_pengguna'), $filename);
            $user->foto_pengguna = $filename;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
