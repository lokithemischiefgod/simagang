<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperadminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.superadmin.admins', compact('admins'));
    }

    public function create()
    {
        return view('admin.superadmin.create_admin');
    }

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

    public function destroy($id)
    {
        $user = auth()->user();

        $admin = User::where('role', 'admin')
        ->where('id', $id)
        ->firstOrFail();    

        if ($user->id === $admin->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return back()->with('success', 'Admin berhasil dihapus.');
    }
    public function promoteToSuperadmin($id)
    {
        $currentUser = auth()->user();

        if ($currentUser->id == $id) {
            return back()->with('error', 'Anda tidak dapat mempromosikan diri sendiri.');
        }

        $admin = User::where('role', 'admin')->findOrFail($id);

        $admin->update([
            'role' => 'superadmin',
            'must_change_password' => true,
        ]);

        return back()->with(
            'success',
            "{$admin->name} berhasil dipromosikan menjadi Superadmin."
        );
    }

}
