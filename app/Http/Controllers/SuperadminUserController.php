<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperadminUserController extends Controller
{
    // Daftar admin
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.superadmin.admins', compact('admins'));
    }

    // Form tambah admin
    public function create()
    {
        return view('admin.superadmin.create_admin');
    }

    // Simpan admin baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin baru berhasil dibuat.');
    }

    // Hapus admin
    public function destroy($id)
    {
        $user = auth()->user();

        $admin = User::where('role', 'admin')->findOrFail($id);

        // Jaga-jaga: jangan sampai superadmin hapus dirinya sendiri (kalau dia admin)
        if ($user->id === $admin->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return back()->with('success', 'Admin berhasil dihapus.');
    }
}
