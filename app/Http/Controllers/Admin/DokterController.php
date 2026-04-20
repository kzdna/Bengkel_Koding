<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\User;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    public function index()
    {
        $jadwals = \App\Models\User::where('role', 'dokter')->get(); 
        return view('admin.dokter.index', compact('jadwals'));
    }

    public function create()
    {
        $poli = Poli::all();
        return view('admin.dokter.create', compact('poli'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->nama, 
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
        ]);

        return redirect()->route('dokter.index')
            ->with('message', 'Data Dokter Berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function edit(string $id)
    {
        $dokter = User::findOrFail($id); 
        $poli = Poli::all();
        return view('admin.dokter.edit', compact('dokter', 'poli'));
    }

    public function update(Request $request, string $id)
    {
        $dokter = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $dokter->id,
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email' => 'required|string|email|unique:users,email,' . $dokter->id,
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name' => $request->nama, 
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $dokter->update($updateData);

        return redirect()->route('dokter.index')
            ->with('message', 'Data Dokter Berhasil diubah')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        $dokter = User::findOrFail($id);
        $dokter->delete();

        return redirect()->route('dokter.index')
            ->with('message', 'Data Dokter Berhasil dihapus')
            ->with('type', 'success');
    }
}