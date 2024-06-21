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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
            'foto_pengguna' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek kata sandi lama
        if (!\Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi lama salah.']);
        }

        // Update user data
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        // Handle foto_pengguna
        if ($request->hasFile('foto_pengguna')) {
            $file = $request->file('foto_pengguna');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_pengguna'), $filename);
            $user->foto_pengguna = $filename;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
