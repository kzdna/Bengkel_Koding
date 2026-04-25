<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = User::where('role', 'pasien')->get();
        return view('admin.pasien.index', compact('pasiens')); 
    }

    public function create()
    {
        return view ('pasien_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pasien',
        ]);

        return redirect()->route('pasien.index')->with('message', 'Data Pasien berhasil di Tambah')->with('type','success');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $pasien = User::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, string $id)
    {
        $pasien = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' .$pasien->id,
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|unique:users,email,' .$pasien->id,
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
        ];

        if($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $pasien->update($updateData);

        return redirect()->route('pasien.index')
            ->with('message', 'Data Pasien Berhasil di Update')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        $pasien = User::findOrFail($id);
        $pasien->delete();
        return redirect()->route('pasien.index')
            ->with('message', 'Data Pasien Berhasil di Hapus')
            ->with('type', 'success');
    }

    public function daftarPeriksa()
    {
        $user = auth()->user(); 
        $polis = \App\Models\Poli::all();
        $jadwals = \App\Models\JadwalPeriksa::with('dokter')->get(); 
        return view('admin.pasien.daftar', compact('user', 'polis', 'jadwals'));
    }

    public function submit(Request $request)
    {
        return back()->with('message', 'Pendaftaran berhasil!');
    }
}