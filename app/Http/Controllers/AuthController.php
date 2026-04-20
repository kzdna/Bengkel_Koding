<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return match($user->role) {
                'admin'  => redirect()->route('admin.dashboard'),
                'dokter' => redirect()->route('dokter.dashboard'),
                'pasien' => redirect()->route('pasien.dashboard'),
                default  => redirect()->route('login'),
            };
        }
        return back()->withErrors(['email' => 'Email atau Password Salah!']);
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required',
            'no_ktp' => 'required',
            'no_hp' => 'required',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        $lastPasien = User::where('role', 'pasien')->orderBy('id', 'desc')->first();
        $lastId = $lastPasien ? $lastPasien->id + 1 : 1;
        $no_rm = date('Ym') . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);

        User::create([
            'name' => $request->nama,     
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'no_rm' => $no_rm,            
            'role' => 'pasien',
            'email' => $request->email,   
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Register berhasil! Silakan login.');
    }

    public function logout() { Auth::logout(); return redirect()->route('login'); }
}