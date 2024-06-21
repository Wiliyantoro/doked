<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'level' => 'required',
            'foto_pengguna' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->level = $request->level;

        if ($request->hasFile('foto_pengguna')) {
            $foto = $request->file('foto_pengguna');
            $nama_foto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('storage/foto_pengguna'), $nama_foto);
            $user->foto_pengguna = $nama_foto;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_name' => 'required',
            'edit_email' => 'required|email|unique:users,email,' . $id,
            'edit_level' => 'required',
            'edit_foto_pengguna' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->edit_name;
        $user->email = $request->edit_email;
        $user->level = $request->edit_level;

        if ($request->hasFile('edit_foto_pengguna')) {
            $foto = $request->file('edit_foto_pengguna');
            $nama_foto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('storage/foto_pengguna'), $nama_foto);
            $user->foto_pengguna = $nama_foto;
        }

        $user->save();

        return response()->json(['success' => 'User berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'User berhasil dihapus!']);
    }
}
