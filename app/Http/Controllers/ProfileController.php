<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'foto_pengguna' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->input('name');

        if ($request->hasFile('foto_pengguna')) {
            // Delete old foto_pengguna if exists
            if ($user->foto_pengguna) {
                Storage::delete('public/foto_pengguna/' . $user->foto_pengguna);
            }

            // Store the new foto_pengguna
            $fotoPath = $request->file('foto_pengguna')->store('public/foto_pengguna');
            $user->foto_pengguna = basename($fotoPath);
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
